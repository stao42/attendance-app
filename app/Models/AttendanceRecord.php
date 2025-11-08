<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'break_time',
        'work_time',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * 勤怠記録に紐づくユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 勤怠記録に紐づく休憩記録を取得
     */
    public function breaks()
    {
        return $this->hasMany(BreakRecord::class);
    }

    /**
     * 勤怠記録に紐づく修正申請を取得
     */
    public function stampCorrectionRequests()
    {
        return $this->hasMany(StampCorrectionRequest::class);
    }

    /**
     * 承認待ちの修正申請があるかどうか
     */
    public function hasPendingCorrectionRequest()
    {
        return $this->stampCorrectionRequests()->where('status', 'pending')->exists();
    }

    /**
     * 現在のステータスを取得
     */
    public function getStatus()
    {
        if (!$this->clock_in) {
            return '勤務外';
        }

        if ($this->clock_out) {
            return '退勤済';
        }

        // 休憩中かどうかをチェック（最後の休憩が開始されていて終了していない場合）
        $lastBreak = $this->breaks()->orderBy('created_at', 'desc')->first();
        if ($lastBreak && $lastBreak->break_start && !$lastBreak->break_end) {
            return '休憩中';
        }

        return '出勤中';
    }

    /**
     * 勤務時間を計算
     */
    public function calculateWorkTime()
    {
        if ($this->clock_in && $this->clock_out) {
            $clockIn = new \DateTime($this->clock_in);
            $clockOut = new \DateTime($this->clock_out);
            $breakTime = $this->break_time ? new \DateTime($this->break_time) : new \DateTime('00:00:00');

            $workTime = $clockIn->diff($clockOut);
            $breakSeconds = $breakTime->format('H') * 3600 + $breakTime->format('i') * 60 + $breakTime->format('s');
            $workSeconds = $workTime->h * 3600 + $workTime->i * 60 + $workTime->s - $breakSeconds;

            if ($workSeconds < 0) {
                $workSeconds = 0;
            }

            $hours = floor($workSeconds / 3600);
            $minutes = floor(($workSeconds % 3600) / 60);
            $seconds = $workSeconds % 60;

            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return '00:00:00';
    }
}
