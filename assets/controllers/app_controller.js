import { Controller } from '@hotwired/stimulus';
import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";

export default class extends Controller {
    static values = { mercureUrl: String }
    connect() {
        this.es = new EventSource(this.mercureUrlValue);
        connectStreamSource(this.es);
    }

    disconnect() {
        if (this.es) {
            this.es.close();
            disconnectStreamSource(this.es);
        }
    }
}