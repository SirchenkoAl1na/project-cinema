// SFS - Search, Filter, Sort
class SFS {
    static getBaseURL() {
        this.url = window.location.href;
        this.search = '';
        this.date = '';
        this.filter = [];
        this.filter2 = [];
        this.sort = '';
        this.grouping = '';
        this.baseURL = '';
        let params = this.url.split('?');
        params = params[1] ? params[1].split('&') : [];
        // read params 
        params.forEach(param_str => {
            //1. split param_str by '='
            let key_and_value = param_str.split('=');
            let key = key_and_value[0];
            let value = key_and_value[1] ? key_and_value[1] : '';

            //2. check if key is valid
            if (key == 'search') this.search = value;
            else if (key == 'filter') this.filter = value.split(',');
            else if (key == 'sort') this.sort = value;
            else if (key == 'grouping') this.grouping = value;
            else if (key == 'sort') this.sort = value;
            else if (key == 'date') this.date = value;
            else if (key == 'filter2') this.filter2 = value.split(',');
            else if (key == 'groupby') this.grouping = value;
        });
        this.baseURL = this.url.split('?')[0];
    }
    static chooseDate(date) {
        this.getBaseURL();
        this.date = date;
        this.redirect();
    }
    static setSearchParams(value) {
        this.getBaseURL();
        this.search = value;
        this.redirect();
    }
    static setFilterParams(value) {
        this.getBaseURL();
        if (this.filter.includes(value)) this.filter = this.filter.filter(item => item !== value);
        else this.filter.push(value);
        this.redirect();
    }
    static setFilterParam(value) {
        this.getBaseURL();
        this.filter = value!=''?[value]:[];
        this.redirect();
    }
    static setFilter2Param(value) {
        this.getBaseURL();
        this.filter2 = value!=''?[value]:[];
        this.redirect();
    }
    static setSortParams(value) {
        this.getBaseURL();
        this.sort = value;
        this.redirect();
    }
    static setGroupingParams(value){
        this.getBaseURL();
        this.grouping = value;
        this.redirect();
    }
    static redirect() {
        let params = [
            this.search != '' ? "search=" + this.search : '',
            this.filter.length > 0 ? "filter=" + this.filter.join(',') : '',
            this.filter2.length > 0 ? "filter2=" + this.filter2.join(',') : '',
            this.date != '' ? "date=" + this.date : '',
            this.sort != '' ? "sort=" + this.sort : '',
            this.grouping != '' ? "groupby=" + this.grouping : '',
        ];
        params = params.filter(param => param !== '');
        if (params.length == 0) location.href = this.baseURL;
        else if (params.length == 1) location.href = this.baseURL + '?' + params[0];
        else location.href = this.baseURL + '?' + params.join('&');
    }
}