document.getElementById("passwordForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Zabránění výchozího chování formuláře
  
    // Získání hodnot z formuláře
    var password = document.getElementById("password").value;
    var complexity = parseInt(document.getElementById("complexity").value);
    
    // Pole se vzorovými hesly pro každou složitost
    var samplePasswords = ["5612", "32276", "bzcd", "h4b2", "z+1c"];
    
    // Funkce pro kontrolu hesla podle složitosti
    function checkPassword(password, complexity) {
      if (password === samplePasswords[complexity]) {
        return true;
      }
      return false;
    }
    
    // Vytvoření FormData objektu pro odeslání dat na server
    var formData = new FormData();
    formData.append("password", password);
    formData.append("complexity", complexity);
  
    // Vytvoření a konfigurace XMLHttpRequest objektu
    var xhr = new XMLHttpRequest();
    var url = "handle_password.php"; // Změňte na skutečný serverový soubor pro zpracování dat
    
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          var resultDisplay = document.getElementById("result");
          resultDisplay.textContent = xhr.responseText;
          resultDisplay.style.color = "green";
        } else {
          console.error("Chyba při odesílání dat na server.");
        }
      }
    };
  
    // Odeslání dat na server
    xhr.send(formData);
  });
  