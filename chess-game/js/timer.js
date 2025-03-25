class Timer {
    constructor(initialSeconds = 600) {
        this.initialTime = initialSeconds;
        this.remainingTime = this.initialTime;
        this.isRunning = false;
        this.interval = null;
        this.onTick = null;
        this.onTimeout = null;
    }

    start() {
        if (!this.isRunning && this.remainingTime > 0) {
            this.isRunning = true;
            this.interval = setInterval(() => {
                if (this.remainingTime > 0) {
                    this.remainingTime--;
                    if (this.onTick) {
                        this.onTick(this.formatTime());
                    }
                } else {
                    this.stop();
                    if (this.onTimeout) {
                        this.onTimeout();
                    }
                }
            }, 1000);
        }
    }

    stop() {
        if (this.isRunning) {
            clearInterval(this.interval);
            this.isRunning = false;
        }
    }

    reset(newTime = null) {
        this.stop();
        this.initialTime = newTime || this.initialTime;
        this.remainingTime = this.initialTime;
        if (this.onTick) {
            this.onTick(this.formatTime());
        }
    }

    formatTime() {
        const minutes = Math.floor(this.remainingTime / 60);
        const seconds = this.remainingTime % 60;
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
} 