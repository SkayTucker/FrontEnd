//Primeiramente, Um Objeto é lógico CONST
const amanda = {
    fName: "Amanda",
    lName: "Freitas"
}

function nomeCompleto() {
    let firstName = amanda.fName;
    let lastName = amanda.lName;

    const nCompleto = firstName + " " + lastName;

    return nCompleto;
}

console.log(nomeCompleto());