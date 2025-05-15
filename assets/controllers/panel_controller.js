import { Controller } from '@hotwired/stimulus'
import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";

export default class extends Controller {
    static values = { url: String };

    connect() {
        this.es = new EventSource(this.urlValue);
        connectStreamSource(this.es);
    }

    disconnect() {
        this.es.close();
        disconnectStreamSource(this.es);
    }
}