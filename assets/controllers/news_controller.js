import { Controller } from '@hotwired/stimulus'
import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";

export default class extends Controller {
    static values = { url: String, newsListUrl: String };

    connect() {
        this.load('forward', '2030-01-01');

        this.es = new EventSource(this.urlValue);
        connectStreamSource(this.es);
    }

    disconnect() {
        this.es.close();
        disconnectStreamSource(this.es);
    }

    load(direction, lastPublishedAt) {
        fetch(this.newsListUrlValue + '?' + (new URLSearchParams({direction, lastPublishedAt})))
            .then(response => response.text())
            .then(html => this.element.innerHTML = html)
    }

    loadNews({params: {direction, lastPublishedAt}}) {
        console.log(direction, lastPublishedAt);
        this.load(direction, lastPublishedAt);
    }
}