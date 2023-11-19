<!DOCTYPE html>
<html>
<head>
    <title>Password Cracker</title>
</head>
<body>
    <h1>Password Cracker</h1>
    <form action="" method="post" onsubmit="checkPassword(event)">
        <label for="password">Enter Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        
        <label for="complexity">Select Complexity:</label><br>
        <select id="complexity" name="complexity">
            <option value="0">0 - 4 numbers</option>
            <option value="1">1 - 5 numbers</option>
            <option value="2">2 - 4 letters</option>
            <option value="3">3 - 4 letters and numbers</option>
            <option value="4">4 - 4 letters, numbers, and special characters</option>
        </select><br><br>
        
        <input type="submit" value="Check Password">
    </form>

    <div id="result"></div>
    <div id="foundPassword"></div>
    <div id="timeTaken"></div>

    <button onclick="crackPassword()">Crack Password</button>

    <script>
        function checkPassword(event) {
            event.preventDefault();
            var password = document.getElementById('password').value;
            var complexity = document.getElementById('complexity').value;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_password.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('result').innerHTML = xhr.responseText;
                }
            };
            xhr.send('password=' + password + '&complexity=' + complexity);
        }

        function crackPassword() {
            var startTime = new Date().getTime();
            var passwordToCrack = document.getElementById('password').value;
            var complexity = document.getElementById('complexity').value;
            var characters = "";
            var maxLength = 0;

            if (complexity === '0') {
                characters = "0123456789";
                maxLength = 4;
            } else if (complexity === '1') {
                characters = "0123456789";
                maxLength = 5;
            } else if (complexity === '2') {
                characters = "abcdefghijklmnopqrstuvwxyz";
                maxLength = 4;
            } else if (complexity === '3') {
                characters = "abcdefghijklmnopqrstuvwxyz0123456789";
                maxLength = 4;
            } else if (complexity === '4') {
                characters = "abcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=[]{}|;:,.<>?";
                maxLength = 4;
            }

            var foundPasswordElement = document.getElementById('foundPassword');
            var passwordFound = false;
            const maxRequestsPerSecond = 700;
            const delayBetweenRequests = 1000 / maxRequestsPerSecond;
            let requestsCounter = 0;

            function generatePasswords(characters, len, prefix) {
                return new Promise((resolve) => {
                    if (passwordFound || requestsCounter >= maxRequestsPerSecond) {
                        setTimeout(() => {
                            resolve(generatePasswords(characters, len, prefix));
                        }, delayBetweenRequests);
                    } else {
                        if (len === 0) {
                            requestsCounter++;
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', 'check_password.php', true);
                            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    if (xhr.responseText === "OK") {
                                        foundPasswordElement.innerHTML = "Password found: " + prefix;
                                        passwordFound = true;
                                        var endTime = new Date().getTime();
                                        var timeTaken = (endTime - startTime) / 1000; // in seconds
                                        document.getElementById('timeTaken').innerHTML = "Time taken: " + timeTaken + " seconds";
                                    }
                                    requestsCounter--;
                                    resolve();
                                }
                            };
                            xhr.send('password=' + prefix + '&complexity=' + complexity);
                            resolve();
                        } else {
                            var requests = [];
                            for (var i = 0; i < characters.length; i++) {
                                requests.push(generatePasswords(characters, len - 1, prefix + characters[i]));
                            }
                            Promise.all(requests).then(() => resolve());
                        }
                    }
                });
            }

            generatePasswords(characters, maxLength, "");
        }
    </script>
</body>
</html>
