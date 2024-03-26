<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title>title | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <div id="password-generator">
        <form action="">
            <label for="lenght">Length: </label>
            <input type="text" name="length" id="length" placeholder="Length" value="8">
            <br>
            <label for="uppercase">Uppercase: </label>
            <input type="checkbox" name="uppercase" id="uppercase" checked>
            <br>
            <label for="lowercase">Lowercase: </label>
            <input type="checkbox" name="lowercase" id="lowercase" checked>
            <br>
            <label for="numbers">Numbers: </label>
            <input type="checkbox" name="numbers" id="numbers" checked>
            <br>
            <label for="symbols">Symbols: </label>
            <input type="checkbox" name="symbols" id="symbols" checked>
            <br>
            <input type="submit" value="Generate">
        </form>

        <div id="password">
            <p>Generated password: </p>
            <p id="password-text"></p>
        </div>

        <script>
            const generatePassword = (length, uppercase, lowercase, numbers, symbols) => {
                let password = '';
                let characters = '';
                if (uppercase) characters += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                if (lowercase) characters += 'abcdefghijklmnopqrstuvwxyz';
                if (numbers) characters += '0123456789';
                if (symbols) characters += '!@#$%^&*()_+~`|}{[]\:;?><,./-=';
                for (let i = 0; i < length; i++) {
                    password += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return password;
            };
            document.querySelector('form').addEventListener('submit', event => {
                event.preventDefault();
                const length = document.querySelector('#length').value;
                const uppercase = document.querySelector('#uppercase').checked;
                const lowercase = document.querySelector('#lowercase').checked;
                const numbers = document.querySelector('#numbers').checked;
                const symbols = document.querySelector('#symbols').checked;
                const password = generatePassword(length, uppercase, lowercase, numbers, symbols);
                document.querySelector('#password-text').innerText = password;
            });
        </script>
    </div>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('base page', 100, title);
    </script>
</body>
</html>