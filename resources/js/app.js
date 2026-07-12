document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('cobabotBtn');
  const popup = document.getElementById('cobabotPopup');
  const close = document.getElementById('cobabotClose');
  const messages = document.getElementById('cobabotMessages');
  const input = document.getElementById('cobabotInput');
  const send = document.getElementById('cobabotSend');
  const badge = document.getElementById('cobabotBadge');

  let isOpen = false;
  const conversation = [];

  function toggle() {
    isOpen = !isOpen;
    btn.classList.toggle('is-open', isOpen);
    popup.classList.toggle('is-open', isOpen);
    if (isOpen) {
      input.focus();
      badge.style.display = 'none';
    }
  }

  function formatTime() {
    const now = new Date();
    return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
  }

  function addMessage(text, role) {
    const div = document.createElement('div');
    div.className = `cobabot-msg ${role}`;
    div.textContent = text;
    const time = document.createElement('div');
    time.className = 'cobabot-msg-time';
    time.textContent = formatTime();
    div.appendChild(time);
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  function addTyping() {
    const div = document.createElement('div');
    div.className = 'cobabot-typing';
    div.id = 'cobabotTyping';
    for (let i = 0; i < 3; i++) {
      const dot = document.createElement('span');
      div.appendChild(dot);
    }
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  function removeTyping() {
    const el = document.getElementById('cobabotTyping');
    if (el) el.remove();
  }

  function addError(msg) {
    const div = document.createElement('div');
    div.className = 'cobabot-msg error';
    div.textContent = msg || 'Maaf, terjadi kesalahan. Silakan coba lagi.';
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  function autoResize() {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 100) + 'px';
  }

  async function sendMessage() {
    const text = input.value.trim();
    if (!text) return;

    input.value = '';
    input.style.height = 'auto';
    send.disabled = true;

    addMessage(text, 'user');
    conversation.push({ role: 'user', text });

    addTyping();

    try {
      const res = await fetch('/api/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ conversation }),
      });

      removeTyping();

      if (!res.ok) {
        addError();
        return;
      }

      const data = await res.json();
      const reply = data.output || '';
      addMessage(reply, 'bot');
      conversation.push({ role: 'model', text: reply });
    } catch {
      removeTyping();
      addError();
    } finally {
      send.disabled = false;
      input.focus();
    }
  }

  btn.addEventListener('click', toggle);
  close.addEventListener('click', toggle);

  send.addEventListener('click', sendMessage);

  input.addEventListener('input', autoResize);

  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  setTimeout(() => {
    if (!isOpen) {
      badge.style.display = 'flex';
    }
  }, 5000);
});
