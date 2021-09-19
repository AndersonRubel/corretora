const convertFunctions = {
    numericToReal: (valor = 0, casasDecimais = 2) => {
        return appFunctions.numberFormat(valor, casasDecimais, ",", ".");
    },
    realToNumeric: (valor = 0) => {
        return valor.replace(".", "").replace(",", ".");
    },
    realToInt: (valor = 0) => {
        return parseInt(valor.replace(/[\D]+/g,""));
    },
    intToReal: (valor = "0", casasDecimais = 2) => {
        valor = valor === null ? "0" : valor;
        if (typeof valor !== "string") valor = valor.toString();

        let value = valor.substr(0, valor.length - 2);
        let resto = valor.substr(valor.length - 2, valor.length);

        valor = Number(`${value}.${resto}`);
        valor = isNaN(valor) ? 0 : valor;
        return Intl.NumberFormat('pt-br', { currency: 'BRL', minimumFractionDigits: casasDecimais }).format(valor)
    },
    intToPhone: (valor) => {
        if(!valor) return valor;
        return valor.replace(/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/, "($1) $2-$3");
    },
    intToCpf: (valor) => {
        return valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
    },
    intToCnpj: (valor) => {
        return valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
    },
    onlyNumber: (str) => {
        if(!str) return str;

        if (typeof str !== 'string') str = str.toString();

        return parseInt(str.replace(/[\D]+/g,''));
    }
}
