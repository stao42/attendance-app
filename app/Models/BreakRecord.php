<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakRecord extends Model
{
    use HasFactory;

    protected $table = 'breaks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'attendance_record_id',
        'break_start',
        'break_end',
        'break_duration',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'break_duration' => 'integer',
        ];
    }

    /**
     * 休憩記録に紐づく勤怠記録を取得
     */
    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    /**
     * 休憩時間を計算（秒数から時分秒形式へ）
     */
    public function getFormattedDuration()
    {
        $hours = floor($this->break_duration / 3600);
        $minutes = floor(($this->break_duration % 3600) / 60);
        $seconds = $this->break_duration % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
