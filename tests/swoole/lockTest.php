<?php

$lock = new swoole_lock(SWOOLE_MUTEX);
echo "[Master]create lock\n";
$lock->lock();
if(pcntl_fork() > 0)
{
    //parent
    sleep(1);
    $lock->unlock();
}
else
{
    // child
    echo "[Child] Wait Lock\n";
    $lock->lock();
    echo "[Child] Get Lock\n";
    $lock->unlock();
    exit("[Child] exit\n");
}
echo "[Master]release lock\n";
unset($lock);
sleep(1);
echo "[Master]exit\n";