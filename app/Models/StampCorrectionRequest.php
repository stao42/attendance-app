<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCorrectionRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'attendance_record_id',
        'user_id',
        'requested_clock_in',
        'requested_clock_out',
        'requested_notes',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'status' => 'string',
        ];
    }

    /**
     * 修正申請に紐づく勤怠記録を取得
     */
    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    /**
     * 修正申請を提出したユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 修正申請を承認した管理者を取得
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * 承認待ちかどうか
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * 承認済みかどうか
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }
}
