import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = { newsListUrl: String };
    static targets = [ "search" ]

    connect() {
        this.initList();
    }

    load(direction, lastPublishedAt) {
        const query = this.hasSearchTarget ? this.searchTarget.value : '';
        const params = (new URLSearchParams({direction, lastPublishedAt, query}));
        fetch(this.newsListUrlValue + '?' + params)
            .then(response => response.text())
            .then(html => this.element.innerHTML = html)
    }

    loadNews({params: {direction, lastPublishedAt}}) {
        this.load(direction, lastPublishedAt);
    }

    initList() {
        this.load('forward', '2030-01-01');
    }
}