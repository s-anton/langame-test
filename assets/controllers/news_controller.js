import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = { newsListUrl: String };

    connect() {
        if (document.documentElement.hasAttribute('data-turbo-preview')) {
            return;
        }
        this.load('forward', '2030-01-01');
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