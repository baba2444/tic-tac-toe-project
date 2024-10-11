<?php
// Start session
session_start();

include('tic-tac-toe-functions.php');

// Initialize the game board 
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, ''); 
    $_SESSION['current_player'] = 'X';
    $_SESSION['winner'] = null; 
}

// Handle button click and update the board
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reset'])) {
        // Reset the game by clearing the session and reloading the page
        session_unset();
        header('Location: tic-tac-toe.php');
        exit();
    } else {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'cell') !== false) {
                $position = str_replace('cell', '', $key); 

                // Only update the board if the spot is empty and there is no winner yet
                if ($_SESSION['board'][$position] === '' && !$_SESSION['winner']) {
                    $_SESSION['board'][$position] = $_SESSION['current_player']; // Place 'X' or 'O'
                    $_SESSION['current_player'] = ($_SESSION['current_player'] === 'X') ? 'O' : 'X'; // Switch player
                }
            }
        }

        // Check for a winner using the function in the included file
        $_SESSION['winner'] = whoIsWinner();

        // Check if the game is a draw (no winner and no more empty spots)
        if (!$_SESSION['winner'] && !in_array('', $_SESSION['board'])) {
            $_SESSION['winner'] = 'Draw';
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe</title>
    <style>
        /* Default button styling */
        button {
            background-color: #3498db;
            height: 100%;
            width: 100%;
            text-align: center;
            font-size: 20px;
            color: white;
            vertical-align: middle;
            border: 0px;
        }

        /* Styles for table cells */
        table td {
            text-align: center;
            vertical-align: middle;
            padding: 0px;
            margin: 0px;
            width: 75px;
            height: 75px;
            font-size: 20px;
            border: 3px solid #040404;
            color: white;
        }

        /* Hover effect */
        button:hover,
        input[type="submit"]:hover,
        button:focus,
        input[type="submit"]:focus {
            background-color: #04469d;
            text-decoration: none;
            outline: none;
        }

        /* X and O styling */
        .x { background-color: green; color: white; }
        .o { background-color: red; color: white; }
    </style>
</head>

<body>
    <h1>Tic Tac Toe</h1>
    <p>Turn: <?php echo $_SESSION['current_player']; ?></p>

    <form method="POST" action="tic-tac-toe.php">
        <table>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <tr>
                    <?php for ($j = 0; $j < 3; $j++): ?>
                        <td>
                            <?php 
                            $pos = $i * 3 + $j;
                            if ($_SESSION['board'][$pos] !== ''): ?>
                                <!-- Button with X or O and background color based on the value -->
                                <button class="<?php echo ($_SESSION['board'][$pos] === 'X') ? 'x' : 'o'; ?>" disabled>
                                    <?php echo $_SESSION['board'][$pos]; ?>
                                </button>
                            <?php else: ?>
                                <!-- Empty button for a move -->
                                <button type="submit" name="cell<?php echo $pos; ?>"> </button>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>

        <?php if ($_SESSION['winner']): ?>
            <p>Winner: <?php echo $_SESSION['winner']; ?></p>
        <?php endif; ?>
        
        <button type="submit" name="reset">Reset</button>
    </form>
</body>
</html>
