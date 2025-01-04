class CurrencyConverter {
    constructor(apiKey) {
        this.apiKey = apiKey;
        this.baseUrl = 'https://api.freecurrencyapi.com/v1';
        this.currencies = ['EUR', 'USD', 'GBP'];
        this.rates = {};
        this.lastFetch = null;
        this.CACHE_DURATION = 3600000; // 1 hora en milisegundos
    }

    async fetchRates() {
        try {
            
            const cachedData = localStorage.getItem('currencyRates');
            const cachedTimestamp = localStorage.getItem('currencyRatesTimestamp');
            
            if (cachedData && cachedTimestamp) {
                const now = new Date().getTime();
                const timestamp = parseInt(cachedTimestamp);
                
              
                if (now - timestamp < this.CACHE_DURATION) {
                    this.rates = JSON.parse(cachedData);
                    return this.rates;
                }
            }

          
            const response = await fetch(
                `${this.baseUrl}/latest?apikey=${this.apiKey}&base_currency=EUR&currencies=${this.currencies.join(',')}`
            );
            const data = await response.json();
            this.rates = data.data;

            // Guardar en localStorage
            localStorage.setItem('currencyRates', JSON.stringify(this.rates));
            localStorage.setItem('currencyRatesTimestamp', new Date().getTime().toString());

            return this.rates;
        } catch (error) {
            console.error('Error fetching rates:', error);
        
            const cachedData = localStorage.getItem('currencyRates');
            if (cachedData) {
                this.rates = JSON.parse(cachedData);
                return this.rates;
            }
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