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
        <label for="lenght">Length: </label>
        <select name="length" id="length">
            <option value="8">8</option>
            <option value="16">16</option>
            <option value="20">20</option>
            <option value="24">24</option>
            <option value="32">32</option>
            <option value="64">64</option>
            <option value="128">128</option>
            <option value="256">256</option>
            <option value="512">512</option>
            <option value="1024">1024</option>
        </select>
        <br>
        <label for="random">Random: </label>
        <input type="radio" name="type" id="random" checked>
        <label for="pronounceable">With words: </label>
        <input type="radio" name="type" id="pronounceable">
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
        <input type="button" value="Generate">

        <div id="password">
            <p>Generated password: </p>
            <p id="password-text" style="word-break: break-all;"></p>
            <button onclick="navigator.clipboard.writeText(document.getElementById('password-text').innerText)">Copy</button>
        </div>

        <script>
            const generatePasswordRandom = (length, uppercase, lowercase, numbers, symbols) => {
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

            const generatePasswordPronounceable = (length) => {
                let password = '';
                let Words = [];
                fetch(`https://random-word-api.herokuapp.com/word?number=${length}&lang=<? echo ($lang !== 'llc') ? $lang : 'en' ; ?>`)
                    .then(response => response.json())
                    .then(words => {
                        words.forEach(word => {
                            word = word.charAt(0).toUpperCase() + word.slice(1);
                            Words.push(word);
                        });
                        document.getElementById('password-text').innerText = Words.join('-');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('password-text').innerText = 'Sorry, the words api is down. Please try again later.';
                    });
            };
        
            document.querySelector('input[type="button"]').addEventListener('click', () => {
                const type = document.querySelector('input[name="type"]:checked').id;
                const length = document.getElementById('length').value;
                const uppercase = document.getElementById('uppercase').checked;
                const lowercase = document.getElementById('lowercase').checked;
                const numbers = document.getElementById('numbers').checked;
                const symbols = document.getElementById('symbols').checked;

                if(type === 'random') {
                    document.getElementById('password-text').innerText = generatePasswordRandom(length, uppercase, lowercase, numbers, symbols);
                } else if(type === 'pronounceable') {
                    document.getElementById('password-text').innerText = generatePasswordPronounceable(length);
                }
            });
        </script>
    </div>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Password Generator', 100, title);
    </script>
</body>
</html>