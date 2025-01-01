class CurrencyConverter {
    constructor(apiKey) {
        this.apiKey = apiKey;
        this.baseUrl = 'https://api.freecurrencyapi.com/v1';
        this.currencies = ['EUR', 'USD', 'GBP'];
        this.rates = {};
    }

    async fetchRates() {
        try {
            const response = await fetch(
                `${this.baseUrl}/latest?apikey=${this.apiKey}&base_currency=EUR&currencies=${this.currencies.join(',')}`
            );
            const data = await response.json();
            this.rates = data.data;
            return this.rates;
        } catch (error) {
            console.error('Error fetching rates:', error);
            return null;
        }
    }

    convertPrice(price, targetCurrency) {
        if (targetCurrency === 'EUR') return price;
        if (!this.rates[targetCurrency]) return price;
        const convertedPrice = Math.round(price * this.rates[targetCurrency] * 100) / 100;
        return convertedPrice;

        
    }
}


window.CurrencyConverter = CurrencyConverter;