document.addEventListener("DOMContentLoaded", () => {
  const chatBtn = document.getElementById("chat-toggle");
  const chatBox = document.getElementById("chat-box");
  const chatForm = document.getElementById("chat-form");
  const chatInput = document.getElementById("chat-input");
  const chatMessages = document.getElementById("chat-messages");

  if (!chatBtn || !chatBox) {
    console.error("Chatbot elements not found");
    return;
  }

  chatBtn.addEventListener("click", () => {
    chatBox.classList.toggle("d-none");
  });

  chatForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const message = chatInput.value.trim();
    if (!message) return;

    // Show user message
    chatMessages.innerHTML += `<div class="text-end"><span class="badge bg-primary">${message}</span></div>`;
    chatInput.value = "";

    // Send to backend
    let res = await fetch("../api/chatbot.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message })
    });
    let data = await res.json();

    // Show bot reply
    chatMessages.innerHTML += `<div class="text-start"><span class="badge bg-secondary">${data.reply}</span></div>`;
    chatMessages.scrollTop = chatMessages.scrollHeight;
  });
});
