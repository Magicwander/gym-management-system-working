class TrainerCard extends HTMLElement {
  constructor() {
    super();
    const shadow = this.attachShadow({ mode: 'open' });

    const style = document.createElement('style');
    style.textContent = `
      .card {
        max-width: 350px;
        animation: fadeSlideUp 1s ease forwards;
        opacity: 0;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.7);
        margin-bottom: 20px;
      }

      .card-header {
        background: rgb(220, 76, 100);
        color: white;
        padding: 10px;
        font-weight: bold;
        text-align: center;
      }

      .card-body {
        height: 490px;
        overflow: hidden;
      }

      .card-body img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
      }

      .card-body img:hover {
        transform: scale(1.05);
      }

      .card-footer {
        padding: 10px;
        text-align: center;
        font-weight: bold;
      }

      .description {
        margin-top: 10px;
        text-align: center;
        color: white;
        font-size: 1rem;
        background-color: #222;
        padding: 15px;
      }

      @keyframes fadeSlideUp {
        0% {
          opacity: 0;
          transform: translateY(40px);
        }
        100% {
          opacity: 1;
          transform: translateY(0);
        }
      }
    `;

    const card = document.createElement('div');
    card.classList.add('card');
    card.innerHTML = `
      <div class="card-header">${this.getAttribute('name')}</div>
      <div class="card-body">
        <img src="${this.getAttribute('img')}" alt="${this.getAttribute('name')}"/>
      </div>
      <div class="card-footer">${this.getAttribute('experience')}</div>
      <div class="description">${this.getAttribute('description')}</div>
    `;

    shadow.appendChild(style);
    shadow.appendChild(card);
  }
}

customElements.define('trainer-card', TrainerCard);
