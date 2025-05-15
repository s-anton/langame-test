import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = { mercureUrl: String }
    connect() {
        this.es = new EventSource(this.mercureUrlValue);
        this.es.addEventListener('message', this.onMessage.bind(this));
    }

    disconnect() {
        if (this.es) {
            this.es.removeEventListener('message', this.onMessage);
            this.es.close();
        }
    }

    onMessage(ev) {
        const data = JSON.parse(ev.data);
        let content = '';
        switch (data.type) {
            case 'user_created':
                content = 'User was created: ' + data.data.username;
                break;
            case 'user_verified':
                content = 'User was verified: ' + data.data.username;
                break;
            case 'news-entry_created':
                content = 'News entry was added: ' + data.data.content;
                break;
            default:
                content = 'Unknown type: ' + JSON.stringify(data.data);
        }
        this.element.innerHTML = `<div class="alert">${content}</div>`;

        setTimeout(() => {
            this.element.innerHTML = '';
        }, 10000)
    }
}