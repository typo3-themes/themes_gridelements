class FucodoTabs extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: 'open' });

    this._onSlotChange = this._onSlotChange.bind(this);

    this.shadowRoot.innerHTML = `
      <style>
        :host {
          display: block;
          font-family: sans-serif;
          --tabs-border-color: #d0d7de;
          --tabs-active-color: #0d6efd;
          --tabs-hover-bg: #f3f4f6;
          --tabs-text-color: #222;
          --tabs-panel-padding: 1rem;
        }

        .tabs-wrap {
          overflow-x: auto;
          overflow-y: hidden;
          -webkit-overflow-scrolling: touch;
          scrollbar-width: thin;
          border-bottom: 1px solid var(--tabs-border-color);
        }

        .tabs {
          display: flex;
          flex-wrap: nowrap;
          gap: 0.25rem;
          min-width: max-content;
        }

        .tab-button {
          appearance: none;
          border: 0;
          border-bottom: 2px solid transparent;
          background: transparent;
          color: var(--tabs-text-color);
          font: inherit;
          padding: 0.875rem 1rem;
          cursor: pointer;
          white-space: nowrap;
          flex: 0 0 auto;
          min-height: 44px;
        }

        .tab-button:hover {
          background: var(--tabs-hover-bg);
        }

        .tab-button:focus-visible {
          outline: 2px solid var(--tabs-active-color);
          outline-offset: -2px;
        }

        .tab-button[aria-selected="true"] {
          border-bottom-color: var(--tabs-active-color);
          font-weight: 600;
        }

        .panel {
          padding: var(--tabs-panel-padding);
        }

        .panel ::slotted(*) {
          display: none;
        }

        .panel ::slotted(.active) {
          display: block;
        }

        @media (max-width: 640px) {
          .tab-button {
            padding: 0.875rem 0.9rem;
          }

          .panel {
            padding: 0.875rem;
          }
        }
      </style>

      <div class="tabs-wrap" part="tabs-wrap">
        <div class="tabs" part="tablist" role="tablist" aria-label="Tabs"></div>
      </div>

      <div class="panel" part="panel">
        <slot></slot>
      </div>
    `;

    this._slot = this.shadowRoot.querySelector('slot');
    this._tablist = this.shadowRoot.querySelector('.tabs');
  }

  connectedCallback() {
    this._slot.addEventListener('slotchange', this._onSlotChange);
    this._render();
  }

  disconnectedCallback() {
    this._slot.removeEventListener('slotchange', this._onSlotChange);
  }

  _onSlotChange() {
    this._render();
  }

  _getPanels() {
    return this._slot
      .assignedElements({ flatten: true })
      .filter(el => el.nodeType === Node.ELEMENT_NODE);
  }

  _render() {
    const panels = this._getPanels();
    this._tablist.innerHTML = '';

    if (!panels.length) return;

    let activeIndex = panels.findIndex(panel => panel.hasAttribute('active'));
    if (activeIndex === -1) activeIndex = 0;

    panels.forEach((panel, index) => {
      const title = panel.getAttribute('title') || `Tab ${index + 1}`;
      const tabId = `tab-${index}`;
      const panelId = `panel-${index}`;
      const isActive = index === activeIndex;

      panel.setAttribute('role', 'tabpanel');
      panel.setAttribute('id', panelId);
      panel.setAttribute('aria-labelledby', tabId);

      if (isActive) {
        panel.classList.add('active');
        panel.removeAttribute('hidden');
      } else {
        panel.classList.remove('active');
        panel.setAttribute('hidden', '');
      }

      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'tab-button';
      button.id = tabId;
      button.setAttribute('role', 'tab');
      button.setAttribute('aria-controls', panelId);
      button.setAttribute('aria-selected', String(isActive));
      button.tabIndex = isActive ? 0 : -1;
      button.textContent = title;

      button.addEventListener('click', () => this.activateTab(index));
      button.addEventListener('keydown', (event) => this._onKeyDown(event, index));

      this._tablist.appendChild(button);
    });

    this._scrollActiveTabIntoView(activeIndex);
  }

  activateTab(index) {
    const panels = this._getPanels();
    const buttons = [...this.shadowRoot.querySelectorAll('.tab-button')];

    panels.forEach((panel, i) => {
      const isActive = i === index;
      panel.classList.toggle('active', isActive);

      if (isActive) {
        panel.removeAttribute('hidden');
        panel.setAttribute('active', '');
      } else {
        panel.setAttribute('hidden', '');
        panel.removeAttribute('active');
      }
    });

    buttons.forEach((button, i) => {
      const isActive = i === index;
      button.setAttribute('aria-selected', String(isActive));
      button.tabIndex = isActive ? 0 : -1;
    });

    if (buttons[index]) {
      buttons[index].focus();
      this._scrollActiveTabIntoView(index);
    }
  }

  _scrollActiveTabIntoView(index) {
    const buttons = [...this.shadowRoot.querySelectorAll('.tab-button')];
    const activeButton = buttons[index];
    if (activeButton) {
      activeButton.scrollIntoView({
        behavior: 'smooth',
        inline: 'center',
        block: 'nearest'
      });
    }
  }

  _onKeyDown(event, index) {
    const panels = this._getPanels();
    const lastIndex = panels.length - 1;
    let nextIndex = index;

    switch (event.key) {
      case 'ArrowRight':
      case 'ArrowDown':
        nextIndex = index >= lastIndex ? 0 : index + 1;
        break;
      case 'ArrowLeft':
      case 'ArrowUp':
        nextIndex = index <= 0 ? lastIndex : index - 1;
        break;
      case 'Home':
        nextIndex = 0;
        break;
      case 'End':
        nextIndex = lastIndex;
        break;
      default:
        return;
    }

    event.preventDefault();
    this.activateTab(nextIndex);
  }
}

customElements.define('fucodo-tabs-simple', FucodoTabs);
