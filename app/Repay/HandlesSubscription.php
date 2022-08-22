<?php

namespace App\Repay;

use App\Models\AccessRight;
use Illuminate\Support\Facades\Hash;

trait HandlesSubscription
{
    public function lives(): bool
    {
        $begin = ($this->trial_start ? $this->trial_start : $this->start);
        if($this->status == 'active' && ($begin < now() && $this->end > now())) return true;
        else return false;
    }

    public function expired(): bool
    {
        if($this->end < now()) return true;
    }

    public function isTrial(): bool
    {
        if(! $this->trial_start) return false;
        if($this->trial_start < now() && $this->start > now()) return true;
        else return false;
    }

    public function activate()
    {
        if($this->status == 'active') return true;
        $this->status = 'active';
        $this->save();
    }

    public function deactivate($reason = 'inactive')
    {
        if($this->status != 'active') return true;
        $this->status = $reason;
        $this->save();
    }

    public function canAccess(AccessRight $accessRight)
    {
        return ($this->type->accessRight->contains($accessRight));
    }

    public function cancel()
    {
        Subscriptor::cancel($this);
    }

    public function deleteSubscription()
    {
        Subscriptor::delete($this);
    }

    public function updateDates($newStart, $duration = null)
    {
        Subscriptor::updateDates($this, $newStart, $duration);
    }

    public function extend($duration = null)
    {
        Subscriptor::extend($duration);
    }

    public function renew($duration = null)
    {
        Subscriptor::renew($this);
    }
}
