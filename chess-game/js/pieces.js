class Piece {
    constructor(color, position) {
        this.color = color;
        this.position = position;
        this.hasMoved = false;
    }

    getValidMoves(board) {
        return [];
    }
}

class Pawn extends Piece {
    getValidMoves(board) {
        const moves = [];
        const direction = this.color === 'white' ? -1 : 1;
        const [row, col] = this.position;

        // Forward move
        if (board[row + direction]?.[col] === null) {
            moves.push([row + direction, col]);
            // Initial two-square move
            if (!this.hasMoved && board[row + 2 * direction]?.[col] === null) {
                moves.push([row + 2 * direction, col]);
            }
        }

        // Captures
        const captures = [[row + direction, col - 1], [row + direction, col + 1]];
        captures.forEach(([r, c]) => {
            if (board[r]?.[c]?.color !== this.color) {
                moves.push([r, c]);
            }
        });

        // En passant
        // Implementation here...

        return moves.filter(([r, c]) => r >= 0 && r < 8 && c >= 0 && c < 8);
    }
}

// Similar implementations for other pieces (Rook, Knight, Bishop, Queen, King)
class Rook extends Piece {
    getValidMoves(board) {
        const moves = [];
        const directions = [[0, 1], [0, -1], [1, 0], [-1, 0]];
        
        for (const [dx, dy] of directions) {
            let [row, col] = this.position;
            while (true) {
                row += dx;
                col += dy;
                if (row < 0 || row > 7 || col < 0 || col > 7) break;
                if (board[row][col] === null) {
                    moves.push([row, col]);
                } else if (board[row][col].color !== this.color) {
                    moves.push([row, col]);
                    break;
                } else {
                    break;
                }
            }
        }
        return moves;
    }
}

class Knight extends Piece {
    getValidMoves(board) {
        const moves = [];
        const [row, col] = this.position;
        const knightMoves = [
            [-2, -1], [-2, 1], [-1, -2], [-1, 2],
            [1, -2], [1, 2], [2, -1], [2, 1]
        ];

        for (const [dx, dy] of knightMoves) {
            const newRow = row + dx;
            const newCol = col + dy;
            
            if (newRow >= 0 && newRow < 8 && newCol >= 0 && newCol < 8) {
                if (!board[newRow][newCol] || board[newRow][newCol].color !== this.color) {
                    moves.push([newRow, newCol]);
                }
            }
        }
        return moves;
    }
}

class Bishop extends Piece {
    getValidMoves(board) {
        const moves = [];
        const directions = [[-1, -1], [-1, 1], [1, -1], [1, 1]];
        
        for (const [dx, dy] of directions) {
            let [row, col] = this.position;
            while (true) {
                row += dx;
                col += dy;
                if (row < 0 || row > 7 || col < 0 || col > 7) break;
                if (board[row][col] === null) {
                    moves.push([row, col]);
                } else if (board[row][col].color !== this.color) {
                    moves.push([row, col]);
                    break;
                } else {
                    break;
                }
            }
        }
        return moves;
    }
}

class Queen extends Piece {
    getValidMoves(board) {
        const moves = [];
        const directions = [
            [-1, -1], [-1, 0], [-1, 1],
            [0, -1],           [0, 1],
            [1, -1],  [1, 0],  [1, 1]
        ];
        
        for (const [dx, dy] of directions) {
            let [row, col] = this.position;
            while (true) {
                row += dx;
                col += dy;
                if (row < 0 || row > 7 || col < 0 || col > 7) break;
                if (board[row][col] === null) {
                    moves.push([row, col]);
                } else if (board[row][col].color !== this.color) {
                    moves.push([row, col]);
                    break;
                } else {
                    break;
                }
            }
        }
        return moves;
    }
}

class King extends Piece {
    getValidMoves(board) {
        const moves = [];
        const directions = [
            [-1, -1], [-1, 0], [-1, 1],
            [0, -1],           [0, 1],
            [1, -1],  [1, 0],  [1, 1]
        ];
        
        for (const [dx, dy] of directions) {
            const newRow = this.position[0] + dx;
            const newCol = this.position[1] + dy;
            
            if (newRow >= 0 && newRow < 8 && newCol >= 0 && newCol < 8) {
                if (!board[newRow][newCol] || board[newRow][newCol].color !== this.color) {
                    moves.push([newRow, newCol]);
                }
            }
        }

        // Castling logic
        if (!this.hasMoved) {
            // Kingside castling
            if (this.canCastle(board, true)) {
                moves.push([this.position[0], this.position[1] + 2]);
            }
            // Queenside castling
            if (this.canCastle(board, false)) {
                moves.push([this.position[0], this.position[1] - 2]);
            }
        }

        return moves;
    }

    canCastle(board, isKingside) {
        const row = this.position[0];
        const col = this.position[1];
        
        // Check if rook has moved
        const rookCol = isKingside ? 7 : 0;
        const rook = board[row][rookCol];
        if (!rook || rook.constructor.name !== 'Rook' || rook.hasMoved) {
            return false;
        }

        // Check if squares between king and rook are empty
        const direction = isKingside ? 1 : -1;
        for (let c = col + direction; isKingside ? c < rookCol : c > rookCol; c += direction) {
            if (board[row][c] !== null) {
                return false;
            }
        }

        // TODO: Check if king is in check or if squares are under attack
        return true;
    }
}

// ... Additional piece classes implementation 