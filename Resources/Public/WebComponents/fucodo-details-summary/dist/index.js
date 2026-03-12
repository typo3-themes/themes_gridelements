class BsAccordionDetails extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: 'open' });
    this._mutationObserver = null;
    this._boundToggle = this._handleToggle.bind(this);
  }

  connectedCallback() {
    this.sourceDetails?.classList.add('light-dom-source');
    this.render();
    this.observeLightDom();
  }

  disconnectedCallback() {
    if (this._mutationObserver) {
      this._mutationObserver.disconnect();
    }
  }

  observeLightDom() {
    this._mutationObserver = new MutationObserver((mutations) => {
      // Nur rendern, wenn Änderungen nicht von uns selbst (Klasse/Open-Status) kommen,
      // oder wir vereinfachen es: rendern nur bei echten Strukturänderungen oder Textänderungen.
      // Da wir aber Attribute wie 'open' im sourceDetails ändern, müssen wir vorsichtig sein.
      const shouldRender = mutations.some(mutation => {
        if (mutation.type === 'attributes') {
          // 'open' Attribut Änderungen ignorieren wir hier, da wir sie selbst setzen
          // und sie im shadow DOM separat gehandhabt werden oder nur den open status spiegeln.
          // Ebenfalls 'class' wegen 'light-dom-source'.
          return mutation.attributeName !== 'open' && mutation.attributeName !== 'class';
        }
        return true;
      });

      if (shouldRender) {
        this.render();
      }
    });
    this._mutationObserver.observe(this, {
      childList: true,
      subtree: true,
      attributes: true,
      characterData: true
    });
  }

  get sourceDetails() {
    return this.querySelector('details');
  }

  render() {
    const source = this.sourceDetails;

    if (!source) {
      this.shadowRoot.innerHTML = `
                <style>
                    :host {
                        display: block;
                    }
                </style>
                <div>Kein &lt;details&gt;-Element im Default-Slot gefunden.</div>
            `;
      return;
    }

    const sourceSummary = source.querySelector('summary');
    if (!sourceSummary) {
      this.shadowRoot.innerHTML = `
                <style>
                    :host {
                        display: block;
                    }
                </style>
                <div>Kein &lt;summary&gt; im &lt;details&gt;-Element gefunden.</div>
            `;
      return;
    }

    const isOpen = source.hasAttribute('open');

    const summaryAttributes = Array.from(source.attributes)
      .filter(attr => attr.name !== 'open')
      .map(attr => `${attr.name}="${this.escapeHtml(attr.value)}"`)
      .join(' ');

    const summaryContent = sourceSummary.innerText;

    const contentNodes = Array.from(source.childNodes).filter(node => node !== sourceSummary);
    const contentHtml = contentNodes
      .map(node => node.nodeType === Node.ELEMENT_NODE ? node.outerHTML : node.textContent)
      .join('');

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
                }

                .accordion {
                    border-radius: var(--bs-accordion-border-radius, 0.375rem);
                    overflow: hidden;
                }

                details.accordion-item {
                    border: var(--bs-accordion-border-width, 1px) solid var(--bs-accordion-border-color, #dee2e6);
                    background: var(--bs-accordion-bg, #fff);
                }

                details.accordion-item + details.accordion-item {
                    border-top: 0;
                }

                summary {
                    list-style: none;
                    display: flex;
                    padding: var(--padding);
                }
                .summary-title {
                    flex-grow: 1;
                }

                summary::-webkit-details-marker {
                    display: none;
                }

                .accordion-button {
                    position: relative;
                    display: flex;
                    align-items: center;
                    width: 5rem;
                    text-align: left;
                    border: 0;
                    border-radius: 0;
                    cursor: pointer;
                    font-size: inherit;
                    color: var(--bs-accordion-btn-color, inherit);
                    background-color: var(--bs-accordion-btn-bg, #fff);
                    transition: var(--bs-accordion-transition, all 0.2s ease-in-out);
                }

                .accordion-button:not(.collapsed) {
                    color: var(--bs-card-cap-color);
                    background-color: var(--bs-card-cap-bg);
                }

                button.accordion-button:focus,
                .accordion-button:focus {
                    box-shadow: inherit;
                    outline: none;
                }

                .accordion-button::after {
                    content: "";
                    margin-left: auto;
                    flex-shrink: 0;
                    width: 1rem;
                    height: 1rem;
                    background-repeat: no-repeat;
                    background-size: 1rem;
                    transition: transform 0.2s ease-in-out;
                    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='red'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
                }

                details[open] > summary .accordion-button::after {
                    transform: rotate(-180deg);
                }

                .accordion-body {
                    padding: var(--padding);
                    border-top: 1px solid var(--bs-accordion-border-color, #dee2e6);
                    background: var(--bs-accordion-body-bg, #fff);
                }

                .light-dom-source {
                    display: none !important;
                }
            </style>

            <div class="accordion">
                <details class="accordion-item panel-heading ${isOpen ? '' : ''}" ${summaryAttributes} ${isOpen ? 'open' : ''}>
                    <summary class="${isOpen ? '' : 'collapsed'}">
                        <div class="summary-title">${summaryContent}</div>
                        <div class="accordion-button"></div>
                    </summary>
                    <div class="accordion-body">
                        ${contentHtml}
                    </div>
                </details>
            </div>
        `;

    const shadowDetails = this.shadowRoot.querySelector('details');
    const shadowSummary = this.shadowRoot.querySelector('summary');

    shadowDetails.removeEventListener('toggle', this._boundToggle);
    shadowDetails.addEventListener('toggle', this._boundToggle);

    shadowSummary.addEventListener('click', () => {
      queueMicrotask(() => {
        shadowSummary.classList.toggle('collapsed', !shadowDetails.open);
      });
    });
  }

  _handleToggle(event) {
    const shadowDetails = event.currentTarget;
    const source = this.sourceDetails;
    const shadowSummary = this.shadowRoot.querySelector('summary');

    if (!source) return;

    if (shadowDetails.open) {
      source.setAttribute('open', '');
    } else {
      source.removeAttribute('open');
    }

    if (shadowSummary) {
      shadowSummary.classList.toggle('collapsed', !shadowDetails.open);
    }
  }

  escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }
}

customElements.define('fucodo-details-summary', BsAccordionDetails);
