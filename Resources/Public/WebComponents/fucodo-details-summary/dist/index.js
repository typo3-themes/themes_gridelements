class BsAccordionDetails extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: 'open' });
  }

  connectedCallback() {
    this.render();
  }

  render() {
    this.shadowRoot.innerHTML = `
            <style>
                :host {
                    display: block;
                    --padding: 0.75rem 0.5rem;
                    --bs-accordion-border-width: 1px;
                    --bs-accordion-border-radius: 0.375rem;
                    --bs-accordion-border-color: #dee2e6;
                    --bs-accordion-btn-color: inherit;
                    --bs-accordion-btn-bg: #fff;
                    --bs-accordion-body-bg: #fff;
                    --bs-accordion-transition: all 0.2s ease-in-out;
                    border-radius: var(--bs-accordion-border-radius, 0.375rem);
                    border: var(--bs-accordion-border-width, 1px) solid var(--bs-accordion-border-color, #dee2e6);
                    background: var(--bs-accordion-bg, #fff);
                    overflow: hidden;
                    display: block;
                }
            </style>
            <style id="injected-styles">
                fucodo-details-summary details summary {
                    list-style: none;
                    display: flex;
                    padding: var(--padding, 0.75rem 0.5rem);
                    cursor: pointer;
                    align-items: center;
                    justify-content: space-between;
                    font-weight: bold;
                }
                fucodo-details-summary details summary::-webkit-details-marker {
                    display: none;
                }
                fucodo-details-summary details summary::after {
                    content: "";
                    width: 1rem;
                    height: 1rem;
                    background-repeat: no-repeat;
                    background-size: 1rem;
                    transition: transform 0.2s ease-in-out;
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='red'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
                }
                fucodo-details-summary details[open] summary::after {
                    transform: rotate(-180deg);
                }
                fucodo-details-summary details > :not(summary) {
                    padding: var(--padding, 0.75rem 0.5rem);
                    background: var(--bs-accordion-body-bg, #fff);
                }
            </style>

            <div class="accordion">
                <slot></slot>
            </div>
        `;

    const injectedStyles = this.shadowRoot.getElementById('injected-styles');
    if (injectedStyles && !document.getElementById('fucodo-details-summary-global-styles')) {
      const style = document.createElement('style');
      style.id = 'fucodo-details-summary-global-styles';
      style.textContent = injectedStyles.textContent;
      document.head.appendChild(style);
    }
  }
}

customElements.define('fucodo-details-summary', BsAccordionDetails);
