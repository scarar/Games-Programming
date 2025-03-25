class ChessGame {
    constructor() {
        this.board = new ChessBoard();
        this.selectedSquare = null;
        this.whiteTimer = new Timer(600); // 10 minutes default
        this.blackTimer = new Timer(600);
        this.useTimer = true;
        this.firstMove = true;
        this.setupTimers();
        this.initializeUI();
        this.setupEventListeners();
    }

    setupTimers() {
        // Setup white timer
        this.whiteTimer.onTick = (time) => {
            document.getElementById('white-timer').textContent = time;
        };
        this.whiteTimer.onTimeout = () => {
            alert('Black wins by timeout!');
            this.resetGame();
        };

        // Setup black timer
        this.blackTimer.onTick = (time) => {
            document.getElementById('black-timer').textContent = time;
        };
        this.blackTimer.onTimeout = () => {
            alert('White wins by timeout!');
            this.resetGame();
        };
    }

    initializeUI() {
        const boardElement = document.getElementById('chessboard');
        for (let row = 0; row < 8; row++) {
            for (let col = 0; col < 8; col++) {
                const square = document.createElement('div');
                square.className = `square ${(row + col) % 2 === 0 ? 'light' : 'dark'}`;
                square.dataset.row = row;
                square.dataset.col = col;
                boardElement.appendChild(square);
            }
        }
        this.renderBoard();
    }

    renderBoard() {
        const squares = document.querySelectorAll('.square');
        squares.forEach(square => {
            const row = parseInt(square.dataset.row);
            const col = parseInt(square.dataset.col);
            const piece = this.board.board[row][col];
            
            square.innerHTML = '';
            if (piece) {
                const pieceColor = piece.color;
                const pieceName = piece.constructor.name.toLowerCase();
                const svgKey = `${pieceColor}_${pieceName}`;
                square.innerHTML = PIECE_SVG[svgKey];
                
                // Add piece class for styling
                const pieceElement = square.firstChild;
                pieceElement.classList.add('piece');
            }
        });
    }

    setupEventListeners() {
        document.getElementById('chessboard').addEventListener('click', (e) => {
            const square = e.target.closest('.square');
            if (!square) return;

            const row = parseInt(square.dataset.row);
            const col = parseInt(square.dataset.col);

            if (this.selectedSquare) {
                const [selectedRow, selectedCol] = this.selectedSquare;
                if (this.movePiece([selectedRow, selectedCol], [row, col])) {
                    // Move was successful, turn and timers are handled in movePiece
                    this.selectedSquare = null;
                    this.clearHighlights();
                } else {
                    // Invalid move, just clear selection
                    this.selectedSquare = null;
                    this.clearHighlights();
                }
            } else {
                const piece = this.board.board[row][col];
                if (piece && piece.color === this.board.currentPlayer) {
                    this.selectedSquare = [row, col];
                    this.highlightValidMoves(piece);
                }
            }
        });

        // Add event listeners for game controls
        document.getElementById('new-game').addEventListener('click', () => {
            this.resetGame();
        });

        document.getElementById('save-game').addEventListener('click', () => {
            this.saveGame();
        });

        document.getElementById('load-game').addEventListener('click', () => {
            this.loadGame();
        });

        // Add timer control listeners
        document.getElementById('use-timer').addEventListener('change', (e) => {
            this.useTimer = e.target.checked;
            if (!this.useTimer) {
                this.whiteTimer.stop();
                this.blackTimer.stop();
            } else if (!this.firstMove) {
                if (this.board.currentPlayer === 'white') {
                    this.whiteTimer.start();
                } else {
                    this.blackTimer.start();
                }
            }
        });

        document.getElementById('time-control').addEventListener('change', (e) => {
            const newTime = parseInt(e.target.value);
            this.whiteTimer.reset(newTime);
            this.blackTimer.reset(newTime);
            if (this.useTimer && this.board.currentPlayer === 'white') {
                this.whiteTimer.start();
            } else if (this.useTimer && this.board.currentPlayer === 'black') {
                this.blackTimer.start();
            }
        });
    }

    resetGame() {
        this.board = new ChessBoard();
        this.selectedSquare = null;
        this.firstMove = true;
        const selectedTime = parseInt(document.getElementById('time-control').value);
        this.whiteTimer.reset(selectedTime);
        this.blackTimer.reset(selectedTime);
        this.updateTurnDisplay();
        this.renderBoard();
    }

    saveGame() {
        const gameState = {
            board: this.board.board.map(row => 
                row.map(piece => piece ? {
                    type: piece.constructor.name,
                    color: piece.color,
                    position: piece.position,
                    hasMoved: piece.hasMoved
                } : null)
            ),
            currentPlayer: this.board.currentPlayer,
            whiteTime: this.whiteTimer.remainingTime,
            blackTime: this.blackTimer.remainingTime,
            useTimer: this.useTimer
        };

        localStorage.setItem('chessgame', JSON.stringify(gameState));
        alert('Game saved successfully!');
    }

    loadGame() {
        const savedGame = localStorage.getItem('chessgame');
        if (!savedGame) {
            alert('No saved game found!');
            return;
        }

        try {
            const gameState = JSON.parse(savedGame);
            this.board = new ChessBoard();
            this.board.currentPlayer = gameState.currentPlayer;

            // Reconstruct the board
            this.board.board = gameState.board.map((row, rowIndex) =>
                row.map((piece, colIndex) => {
                    if (!piece) return null;
                    const newPiece = new (eval(piece.type))(piece.color, [rowIndex, colIndex]);
                    newPiece.hasMoved = piece.hasMoved;
                    return newPiece;
                })
            );

            // Restore timers
            this.whiteTimer.remainingTime = gameState.whiteTime;
            this.blackTimer.remainingTime = gameState.blackTime;
            this.whiteTimer.onTick?.(this.whiteTimer.formatTime());
            this.blackTimer.onTick?.(this.blackTimer.formatTime());

            // Add timer state loading
            this.useTimer = gameState.useTimer;
            document.getElementById('use-timer').checked = this.useTimer;

            this.firstMove = false;
            this.updateTurnDisplay();
            
            // Start the appropriate timer if timer is enabled
            if (this.useTimer) {
                if (this.board.currentPlayer === 'white') {
                    this.whiteTimer.start();
                } else {
                    this.blackTimer.start();
                }
            }

            this.renderBoard();
            alert('Game loaded successfully!');
        } catch (error) {
            alert('Error loading game!');
            console.error(error);
        }
    }

    movePiece(from, to) {
        const [fromRow, fromCol] = from;
        const [toRow, toCol] = to;
        
        // Check if it's the correct player's turn
        const piece = this.board.board[fromRow][fromCol];
        if (!piece || piece.color !== this.board.currentPlayer) {
            return false;
        }
        
        if (this.board.movePiece(from, to)) {
            // Handle first move
            if (this.firstMove && this.useTimer) {
                this.firstMove = false;
                this.whiteTimer.start(); // Start white's timer on first move
            }
            
            // Switch timers based on the new current player
            if (this.useTimer) {
                if (this.board.currentPlayer === 'black') { // Now it's Black's turn
                    this.whiteTimer.stop();
                    this.blackTimer.start();
                } else { // Now it's White's turn
                    this.blackTimer.stop();
                    this.whiteTimer.start();
                }
            }
            
            this.updateTurnDisplay();
            this.renderBoard();
            return true;
        }
        return false;
    }

    highlightValidMoves(piece) {
        this.clearHighlights();
        const validMoves = piece.getValidMoves(this.board.board);
        validMoves.forEach(([row, col]) => {
            const square = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
            square.classList.add('valid-move');
        });
    }

    clearHighlights() {
        document.querySelectorAll('.valid-move').forEach(square => {
            square.classList.remove('valid-move');
        });
    }

    updateTurnDisplay() {
        const whitePlayer = document.getElementById('white-player');
        const blackPlayer = document.getElementById('black-player');
        const whiteTimer = document.getElementById('white-timer');
        const blackTimer = document.getElementById('black-timer');

        // Remove all active classes first
        whitePlayer.classList.remove('active');
        blackPlayer.classList.remove('active');
        whiteTimer.classList.remove('active');
        blackTimer.classList.remove('active');

        // Update based on current player
        if (this.board.currentPlayer === 'white') {
            whitePlayer.textContent = "► White's Turn ◄";
            whitePlayer.classList.add('active');
            blackPlayer.textContent = "Black";
            whiteTimer.classList.add('active');
        } else {
            blackPlayer.textContent = "► Black's Turn ◄";
            blackPlayer.classList.add('active');
            whitePlayer.textContent = "White";
            blackTimer.classList.add('active');
        }
    }
}

// Initialize the game
document.addEventListener('DOMContentLoaded', () => {
    new ChessGame();
}); 