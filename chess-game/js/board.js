class ChessBoard {
    constructor() {
        this.board = Array(8).fill(null).map(() => Array(8).fill(null));
        this.selectedPiece = null;
        this.currentPlayer = 'white';
        this.initializeBoard();
    }

    initializeBoard() {
        // Initialize pawns
        for (let i = 0; i < 8; i++) {
            this.board[1][i] = new Pawn('black', [1, i]);
            this.board[6][i] = new Pawn('white', [6, i]);
        }

        // Initialize other pieces
        const backRow = [Rook, Knight, Bishop, Queen, King, Bishop, Knight, Rook];
        for (let i = 0; i < 8; i++) {
            this.board[0][i] = new backRow[i]('black', [0, i]);
            this.board[7][i] = new backRow[i]('white', [7, i]);
        }
    }

    movePiece(from, to) {
        const [fromRow, fromCol] = from;
        const [toRow, toCol] = to;
        
        const piece = this.board[fromRow][fromCol];
        if (!piece || piece.color !== this.currentPlayer) {
            return false;
        }

        const validMoves = piece.getValidMoves(this.board);
        if (!validMoves.some(([r, c]) => r === toRow && c === toCol)) {
            return false;
        }

        // Move the piece
        this.board[toRow][toCol] = piece;
        this.board[fromRow][fromCol] = null;
        piece.position = [toRow, toCol];
        piece.hasMoved = true;

        // Switch players
        this.currentPlayer = this.currentPlayer === 'white' ? 'black' : 'white';
        return true;
    }

    isCheck(color) {
        // Implementation for checking if the king is in check
    }

    isCheckmate(color) {
        // Implementation for checking if the position is checkmate
    }
} 