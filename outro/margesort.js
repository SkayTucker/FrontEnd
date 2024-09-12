function mergeSort(arr) {
    if (arr.length <= 1) {
      return arr;  // Caso base: um array de tamanho 0 ou 1 já está ordenado.
    }
  
    // Dividir: encontrar o meio da lista
    const meio = Math.floor(arr.length / 2);
    const esquerda = arr.slice(0, meio);  // Sublista esquerda
    const direita = arr.slice(meio);  // Sublista direita
  
    // Conquistar: ordenar cada sublista de forma recursiva
    return merge(mergeSort(esquerda), mergeSort(direita));
  }
  
  function merge(esquerda, direita) {
    let resultado = [];
    let i = 0;
    let j = 0;
  
    // Combinar: mesclar os arrays de forma ordenada
    while (i < esquerda.length && j < direita.length) {
      if (esquerda[i] < direita[j]) {
        resultado.push(esquerda[i]);
        i++;
      } else {
        resultado.push(direita[j]);
        j++;
      }
    }
  
    // Adicionar os elementos restantes (se houver)
    return resultado.concat(esquerda.slice(i)).concat(direita.slice(j));
  }
  
  // Exemplo de uso
  const lista = [34, 7, 23, 32, 5];
  const listaOrdenada = mergeSort(lista);
  console.log(listaOrdenada);  // Saída: [5, 7, 23, 32, 34]
  