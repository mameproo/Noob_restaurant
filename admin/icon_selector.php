<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food and Drink Icons</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
      padding-top: 60px;
    }

    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 600px;
      position: relative;
    }

    .close-btn {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      position: absolute;
      top: 10px;
      right: 20px;
      cursor: pointer;
    }

    .close-btn:hover {
      color: black;
    }

    .icon-list div {
      margin-bottom: 15px;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #f4f4f4;
      padding: 10px;
      border-radius: 5px;
    }

    .icon-list i {
      margin-right: 10px;
      font-size: 24px;
    }

    .copy-btn {
      padding: 5px 10px;
      font-size: 14px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    .copy-btn:hover {
      background-color: #45a049;
    }

    #openModal {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
    }

    #openModal:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

  <button id="openModal">Show Food & Drink Icons</button>

  <div id="iconModal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2>Food & Drink Icons</h2>
      <div class="icon-list">
        <div><i class="fa fa-coffee"></i> <span>fa-coffee</span> <button class="copy-btn" onclick="copyText('fa fa-coffee', this)">Copy</button></div>
        <div><i class="fa fa-apple-alt"></i> <span>fa-apple-alt</span> <button class="copy-btn" onclick="copyText('fa fa-apple-alt', this)">Copy</button></div>
        <div><i class="fa fa-beer"></i> <span>fa-beer</span> <button class="copy-btn" onclick="copyText('fa fa-beer', this)">Copy</button></div>
        <div><i class="fa fa-pizza-slice"></i> <span>fa-pizza-slice</span> <button class="copy-btn" onclick="copyText('fa fa-pizza-slice', this)">Copy</button></div>
        <div><i class="fa fa-ice-cream"></i> <span>fa-ice-cream</span> <button class="copy-btn" onclick="copyText('fa fa-ice-cream', this)">Copy</button></div>
        <div><i class="fa fa-hamburger"></i> <span>fa-hamburger</span> <button class="copy-btn" onclick="copyText('fa fa-hamburger', this)">Copy</button></div>
        <div><i class="fa fa-hotdog"></i> <span>fa-hotdog</span> <button class="copy-btn" onclick="copyText('fa fa-hotdog', this)">Copy</button></div>
        <div><i class="fa fa-donut"></i> <span>fa-donut</span> <button class="copy-btn" onclick="copyText('fa fa-donut', this)">Copy</button></div>
        <div><i class="fa fa-glass-martini"></i> <span>fa-glass-martini</span> <button class="copy-btn" onclick="copyText('fa fa-glass-martini', this)">Copy</button></div>
        <div><i class="fa fa-glass-martini-alt"></i> <span>fa-glass-martini-alt</span> <button class="copy-btn" onclick="copyText('fa fa-glass-martini-alt', this)">Copy</button></div>
        <div><i class="fa fa-wine-glass"></i> <span>fa-wine-glass</span> <button class="copy-btn" onclick="copyText('fa fa-wine-glass', this)">Copy</button></div>
        <div><i class="fa fa-bacon"></i> <span>fa-bacon</span> <button class="copy-btn" onclick="copyText('fa fa-bacon', this)">Copy</button></div>
        <div><i class="fa fa-carrot"></i> <span>fa-carrot</span> <button class="copy-btn" onclick="copyText('fa fa-carrot', this)">Copy</button></div>
        <div><i class="fa fa-cake"></i> <span>fa-cake</span> <button class="copy-btn" onclick="copyText('fa fa-cake', this)">Copy</button></div>
        <div><i class="fa fa-fish"></i> <span>fa-fish</span> <button class="copy-btn" onclick="copyText('fa fa-fish', this)">Copy</button></div>
        <div><i class="fa fa-apple"></i> <span>fa-apple</span> <button class="copy-btn" onclick="copyText('fa fa-apple', this)">Copy</button></div>
        <div><i class="fa fa-bread-slice"></i> <span>fa-bread-slice</span> <button class="copy-btn" onclick="copyText('fa fa-bread-slice', this)">Copy</button></div>
        <div><i class="fa fa-pepper-hot"></i> <span>fa-pepper-hot</span> <button class="copy-btn" onclick="copyText('fa fa-pepper-hot', this)">Copy</button></div>
        <div><i class="fa fa-mug-hot"></i> <span>fa-mug-hot</span> <button class="copy-btn" onclick="copyText('fa fa-mug-hot', this)">Copy</button></div>
      </div>
    </div>
  </div>

  <script>
    const modal = document.getElementById("iconModal");
    const openModalButton = document.getElementById("openModal");
    const closeBtn = document.getElementsByClassName("close-btn")[0];

    openModalButton.onclick = function () {
      modal.style.display = "block";
    }

    closeBtn.onclick = function () {
      modal.style.display = "none";
    }

    window.onclick = function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    }

    function copyText(text, button) {
      const tempInput = document.createElement("input");
      document.body.appendChild(tempInput);
      tempInput.value = text;
      tempInput.select();
      document.execCommand("copy");
      document.body.removeChild(tempInput);

      button.innerText = "Copied";
      button.disabled = true;
      setTimeout(() => {
        button.innerText = "Copy";
        button.disabled = false;
      }, 1500);
    }
  </script>
</body>
</html> 