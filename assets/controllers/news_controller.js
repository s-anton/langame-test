import { Controller } from '@hotwired/stimulus'
import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";

export default class extends Controller {
    static values = { url: String, newsListUrl: String };

    connect() {
        if (document.documentElement.hasAttribute('data-turbo-preview')) {
            return;
        }
        this.load('forward', '2030-01-01');

        this.es = new EventSource(this.urlValue);
        connectStreamSource(this.es);
    }

    disconnect() {
        if (this.es) {
            this.es.close();
            disconnectStreamSource(this.es);
        }
    }

    load(direction, lastPublishedAt) {
        fetch(this.newsListUrlValue + '?' + (new URLSearchParams({direction, lastPublishedAt})))
            .then(response => response.text())
            .then(html => this.element.innerHTML = html)
    }

    loadNews({params: {direction, lastPublishedAt}}) {
        this.load(direction, lastPublishedAt);
    }
}