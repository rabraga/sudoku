<HTML>
   <SCRIPT>
   
      // ########### Variaveis globais
      var num_sel; // numero selecionado
      var num_sel_ant; // numero selecionado anterior
      var op_sel,op_sel_ant; // onclick
      var op_sel_ant2; // onmouseover
      var op_erro = new String; // lista com opcoes erradas
      var op_abertas = new String; // opcoes que o jogador ira preencher
      // controle de pontuacao e tempo
      var tentativas = 0;
      var erros = 0;
      // cores
      var correto = '#90EE90'; // verde
      var selecionado = '#B5B5B5'; // cinza
      var destaque = '#CFCFCF'; // cinza claro
      // array com os valores em cada posica com 9x9x9
      var numeros = new Array(9);
      for(var q=1;q<=9;q++) {
         numeros[q] = new Array(9);
         for(var n=1;n<=9;n++) {
            numeros[q][n] = new Array(9);
         }
      }
      // ########### Funcoes
      // variaveis para o tempo
      var tempo_total;
      var tempo_seg = -1;
      var tempo_min = "0"+0;
      var tempo_hor = "0"+0;
      var temporizador;
      // conta o tempo de jogo
      function conta() {
         tempo_seg++;
         if (tempo_seg == 60) {
            tempo_seg = 0;
            tempo_min++;
            if (tempo_min <= 9) tempo_min = "0"+tempo_min;
         }
         if (tempo_min == 60) {
            tempo_min = "0"+0;
            tempo_hor++;
            if (tempo_hor <= 9) tempo_hor = "0"+tempo_hor;
         }
         if (tempo_seg <= 9) tempo_seg = "0"+tempo_seg;
         tempo_total = "" + tempo_hor + ":" + tempo_min + ":" + tempo_seg + ""
         document.getElementById('tempo').innerHTML = tempo_total;
      }
      // funcao q atualiza o tempo
      function atual() {
         conta();
         temporizador = setTimeout("atual()",1000);
      }
      // verifica se o valor esta na lista
      function string_na_lista(valor,lista) {
         var i;
         var aux = new String(lista);
         aux = aux.split(',');
         for (i in aux) {
            if (aux[i] && valor==aux[i]) return true;
         }
         return false;
      }
      // colore o numero escolhido
      function seleciona_num(n) {
         var o; 
         // limpa a opcao anterior
         o = document.getElementById('sel_num_'+num_sel);
         if (o) o.style.backgroundColor = '';
         num_sel = n;
         // colore a opcao escolhida
         o = document.getElementById('sel_num_'+n);
         o.style.backgroundColor = selecionado;
         if (string_na_lista(op_sel,op_abertas)) {
            // seta a opcaoãcom com o valor do numero selecionado
            o = document.getElementById('sel_op_'+op_sel);
            num_sel_ant = o.innerHTML; // numero selecionado anterior
            if (o) o.innerHTML = (n==0?'&nbsp;':n);
            if (n > 0 && n != num_sel_ant) sum_tentativas();
         }
         var op_sel_aux = new String(op_sel);
         // verifica se o numero esta correto, se nao, concatena na lista de opcoe erradas.
         if (verifica_erro(op_sel,n))sum_erros();
         if (verifica_erro(op_sel,n) && !string_na_lista(op_sel,op_erro)) op_erro+= (op_erro.length>0?',':'')+op_sel;
         else if (!verifica_erro(op_sel,n)) op_erro = retira_erro(op_erro);
         // colore a opção errada de vermelho
         if (op_erro.length > 0) seta_erro(op_erro);
      }
      // contabiliza as tentativa
      function sum_tentativas() {
         tentativas++;
         var o = document.getElementById('tentativas');
         o.innerHTML = tentativas;
      }
      // contabiliza os erros
      function sum_erros() {
         erros++;
         var o = document.getElementById('erros');
         o.innerHTML = erros;
      }
      // retira erro da opcao
      function retira_erro(op_erro) {
         var op_erro_aux = Array();
         op_erro_aux = op_erro.split(',');
         op_erro = '';
         for (i in op_erro_aux) {
            if (op_erro_aux[i] != op_sel) op_erro+= (op_erro.length>0?',':'')+op_erro_aux[i];
         }
         // se nao tem erro, tira o vermelho da opcao
         o = document.getElementById('sel_op_'+op_sel);
         o.style.backgroundColor = correto;
         return op_erro;
      }
      // verifica se o numero escolhido esta correto para a opcao
      function verifica_erro(op_sel,n) {
         var op_sel_aux = new String(op_sel);
         var q = new String;
         var l = new String;
         var c = new String;
         // q = quadrado, l = linha, c = coluna
         q = op_sel_aux.substr(0,1);
         l = op_sel_aux.substr(1,1);
         c = op_sel_aux.substr(2,1);
         if (numeros[q][l][c] != n && string_na_lista(q+l+c,op_abertas)) return true;
         else return false;
      }
      // colore as opções que estao erradas
      function seta_erro(op_erro) {
         var op_erro_aux = Array();
         op_erro_aux = op_erro.split(',');
         for (i in op_erro_aux) {
            o = document.getElementById('sel_op_'+op_erro_aux[i]);
            o.style.backgroundColor = 'red';
         }
         return;
      }
      // colore a opção clicada com o mouse
      function seleciona_op(op) {
         var o = document.getElementById('sel_op_'+op);
         if (o) o.style.backgroundColor = selecionado;
         op_sel = op;
         var o = document.getElementById('sel_op_'+op_sel_ant);
         if (o && op > 9) o.style.backgroundColor = ''; //  se esta no quadrado e muda para numero nao apaga a selecao do quadrado
         if (op > 9) destaca_qlc(op_sel);
         op_sel_ant = op_sel;
         // colore a opção errada de vermelho
         if (op_erro.length > 0) seta_erro(op_erro);
      }
      // muda a cor da opcao quando o mouse e passado em cima
      function muda_cor_op(op) {
  //       var o = document.getElementById('teste');
    //     o.innerHTML = 'op: '+op+' op_sel_ant2: '+op_sel_ant2+' op_sel: '+op_sel+' op_sel_ant: '+op_sel_ant;
         // apaga o anterior, diferente do selecionado
         if (op_sel_ant2 && op_sel_ant2 != op && op_sel_ant2 != op_sel) {
            if (op_sel_ant2 > 9 ) document.getElementById('sel_op_'+op_sel_ant2).style.backgroundColor='';
            else document.getElementById('sel_num_'+op_sel_ant2).style.backgroundColor='';
         }
         if (op_sel) destaca_qlc(op_sel);
         // colore onde o mouse esta em cima
         if (op) {
            if (op > 9) {
               document.getElementById('sel_op_'+op).style.backgroundColor = selecionado;
               document.getElementById('sel_op_'+op).style.cursor='pointer';
            } else {
               document.getElementById('sel_num_'+op).style.backgroundColor = selecionado;
               document.getElementById('sel_num_'+op).style.cursor='pointer';
            }
         }
         op_sel_ant2 = op;
         if (op_erro.length > 0) seta_erro(op_erro);
      }
      // retorna as opções do quadrado 
      function retorna_op_quadrado(op_sel) {
         var op_sel_aux = new String(op_sel);
         var opcoes = new String;
         var op = new String;
         var q = new String;
         var l = new String;
         var c = new String;
         // q = quadrado, l = linha, c = coluna
         q = op_sel_aux.substr(0,1);
         l = op_sel_aux.substr(1,1);
         c = op_sel_aux.substr(2,1);
         // 
         for (var x=1;x<=3;x++) {
            for (var y=1;y<=3;y++) {
               op = q+x+y;
               opcoes+= (opcoes.length>0?',':'')+op;
            }
         }
         return(opcoes);
      }
      // retorna as opções da linha
      function retorna_op_linha(op_sel) {
         var op_sel_aux = new String(op_sel);
         var opcoes = new String;
         var op = new String;
         var q = new String;
         var l = new String;
         var c = new String;
         // q = quadrado, l = linha, c = coluna
         q = op_sel_aux.substr(0,1);
         l = op_sel_aux.substr(1,1);
         c = op_sel_aux.substr(2,1);
         // sempre percorre 3 quadrados (1,4,7), (2,5,8) ou (3,6,9)
         for (var i=1;i<=3;i++) { 
            for (var y=1;y<=3;y++) {
               if (q == 1 || q == 4 || q == 7) {
                  for (var qua=1;qua<=7;qua++) {
                     if (qua == 2) qua = 4;
                     else if (qua == 5) qua = 7;
                     op = qua+''+l+y;
                     opcoes+= (opcoes.length>0?',':'')+op;
                  }
               } else if (q == 2 || q == 5 || q == 8) {
                  for (var qua=2;qua<=8;qua++) {
                     if (qua == 3) qua = 5;
                     else if (qua == 6) qua = 8;
                     op = qua+''+l+y;
                     opcoes+= (opcoes.length>0?',':'')+op;
                  }
               } else if (q == 3 || q == 6 || q == 9) {
                  for (var qua=3;qua<=9;qua++) {
                     if (qua == 4) qua = 6;
                     else if (qua == 7) qua = 9;
                     op = qua+''+l+y;
                     opcoes+= (opcoes.length>0?',':'')+op;
                  }
               }
            }
         }
         return(opcoes);
      }
      // retorna as opções da coluna
      function retorna_op_coluna(op_sel) {
         var op_sel_aux = new String(op_sel);
         var opcoes = new String;
         var op = new String;
         var q = new String;
         var l = new String;
         var c = new String;
         // q = quadrado, l = linha, c = coluna
         q = op_sel_aux.substr(0,1);
         l = op_sel_aux.substr(1,1);
         c = op_sel_aux.substr(2,1);
         // sempre percorre 3 quadrados (1,2,3), (4,5,6) ou (7,8,9)
         for (var i=1;i<=3;i++) { 
            for (var x=1;x<=3;x++) {
               if (q == 1 || q == 2 || q == 3) {
                  for (var qua=1;qua<=3;qua++) {
                     op = qua+''+x+''+c;
                     opcoes+= (opcoes.length>0?',':'')+op;
                  }
               } else if (q == 4 || q == 5 || q == 6) {
                  for (var qua=4;qua<=6;qua++) {
                     op = qua+''+x+''+c;
                     opcoes+= (opcoes.length>0?',':'')+op;
                  }
               } else if (q == 7 || q == 8 || q == 9) {
                  for (var qua=7;qua<=9;qua++) {
                     op = qua+''+x+''+c;
                     opcoes+= (opcoes.length>0?',':'')+op;
                  }
               }
            }
         }
         return(opcoes);
      }
      // destaca o quadrado, linha e coluna da opção selecionada
      function destaca_qlc(op_sel) {
         var op_sel_aux = new String(op_sel);
         // q = quadrado, l = linha, c = coluna
         q = op_sel_aux.substr(0,1);
         l = op_sel_aux.substr(1,1);
         c = op_sel_aux.substr(2,1);
         limpa_cor();
         // pega as opcoes do quadrado, linha e coluna
         var op_aux = retorna_op_quadrado(op_sel)+','+retorna_op_linha(op_sel)+','+retorna_op_coluna(op_sel);
         op_aux = op_aux.split(',');
         for (i in op_aux) {
            var o = document.getElementById('sel_op_'+op_aux[i]);
            o.style.backgroundColor = destaque;
         }
         // colore o selecionado, pois limpou as cores de todos no início da função
         var o = document.getElementById('sel_op_'+op_sel);
         if (o) o.style.backgroundColor = selecionado;
      }
      // gera números aleatórios
      function rand(min, max) {
         var numero = Math.round(Math.random()*10);
         while (numero < min || numero > max) {
            numero = Math.round(Math.random()*10);
         }
         return numero;
      }
      function limpa_numeros() {
         for (var q=1;q<=9;q++) {
            for (var x=1;x<=3;x++) {
               for (var y=1;y<=3;y++) {
                  var o = document.getElementById('sel_op_'+q+x+y);
                  numeros[q][x][y] = '';
                  o.innerHTML = '&nbsp;';
               }
            }
         }
      }
      function limpa_cor() {
         for (var q=1;q<=9;q++) {
            for (var x=1;x<=3;x++) {
               for (var y=1;y<=3;y++) {
                  var o = document.getElementById('sel_op_'+q+x+y);
                  if (o) o.style.backgroundColor = '';
               }
            }
         }
      }
      // verifica se o número passado já existe no quadrado.
      function existe_q(q,numero) {
         var existe = false;
         for (var x=1;x<=3;x++) {
            for (var y=1;y<=3;y++) {
               if (numeros[q][x][y] == numero) return true;
            }
         }
         return false;
      }
      // verifica se o número passado já existe na linha
      function existe_l(q,l,numero) {
         var existe = false;
         // sempre percorre 3 quadrados (1,4,7), (2,5,8) ou (3,6,9)
         for (var i=1;i<=3;i++) { 
            for (var y=1;y<=3;y++) {
               if (q == 1 || q == 4 || q == 7) {
                  if (numeros[1][l][y] && numeros[1][l][y] == numero) return true;
                  else if (numeros[4][l][y] && numeros[4][l][y] == numero) return true;
                  else if (numeros[7][l][y] && numeros[7][l][y] == numero) return true;
               } else if (q == 2 || q == 5 || q == 8) {
                  if (numeros[2][l][y] && numeros[2][l][y] == numero) return true;
                  else if (numeros[5][l][y] && numeros[5][l][y] == numero) return true;
                  else if (numeros[8][l][y] && numeros[8][l][y] == numero) return true;
               } else if (q == 3 || q == 6 || q == 9) {
                  if (numeros[3][l][y] && numeros[3][l][y] == numero) return true;
                  else if (numeros[6][l][y] && numeros[6][l][y] == numero) return true;
                  else if (numeros[9][l][y] && numeros[9][l][y] == numero) return true;
               }
            }
         }
         return false;
      }
      // verifica se o número passado já existe na coluna
      function existe_c(q,c,numero) {
         var existe = false;
         // sempre percorre 3 quadrados (1,2,3), (4,5,6) ou (7,8,9)
         for (var i=1;i<=3;i++) { 
            for (var x=1;x<=3;x++) {
               if (q == 1 || q == 2 || q == 3) {
                  if (numeros[1][x][c] && numeros[1][x][c] == numero) return true;
                  else if (numeros[2][x][c] && numeros[2][x][c] == numero) return true;
                  else if (numeros[3][x][c] && numeros[3][x][c] == numero) return true;
               } else if (q == 4 || q == 5 || q == 6) {
                  if (numeros[4][x][c] && numeros[4][x][c] == numero) return true;
                  else if (numeros[5][x][c] && numeros[5][x][c] == numero) return true;
                  else if (numeros[6][x][c] && numeros[6][x][c] == numero) return true;
               } else if (q == 7 || q == 8 || q == 9) {
                  if (numeros[7][x][c] && numeros[7][x][c] == numero) return true;
                  else if (numeros[8][x][c] && numeros[8][x][c] == numero) return true;
                  else if (numeros[9][x][c] && numeros[9][x][c] == numero) return true;
               }
            }
         }
         return false;
      }          
      // função que retorna o valor da tecla passado por parâmetro
      function retorna_numero (k) {
         numero = '';
         if (k == 97 || k == 49) numero = 1;
         if (k == 98 || k == 50) numero = 2;
         if (k == 99 || k == 51) numero = 3;
         if (k == 100 || k == 52) numero = 4;
         if (k == 101 || k == 53) numero = 5;
         if (k == 102 || k == 54) numero = 6;
         if (k == 103 || k == 55) numero = 7;
         if (k == 104 || k == 56) numero = 8;
         if (k == 105 || k == 57) numero = 9;
         return numero;
      }
      document.onkeydown = monitora_teclado;
      // Monitora o teclado
      function monitora_teclado(e) {
         if (!e) var e = window.event;
         if (e.keyCode) k = e.keyCode;
         else if (e.which) k = e.which;
         // setas para cima, baixo, esquerda e direita
         if (k >= 37 && k <= 40) {
            // apaga o anterior, diferente do selecionado
            if (op_sel_ant2 && op_sel_ant2 != op_sel) {
               document.getElementById('sel_op_'+op_sel_ant2).style.backgroundColor='';
            }
            var q,x,y;
            var op_sel_aux = new String(op_sel_ant);
            if (!op_sel_aux || op_sel_aux == 'undefined') seleciona_op(111);
            else {
               // q = quadrado, l = linha, c = coluna
               q = op_sel_aux.substr(0,1);
               l = op_sel_aux.substr(1,1);
               c = op_sel_aux.substr(2,1);
               if (k == 39) { // direita
                  if (c != 3) c++;
                  else if (q != 7 && q != 8 && q != 9) {
                     q = 1*q+3;
                     c = 1;
                  } 
               } else if (k == 37) { // esquerda
                  if (c != 1) c--;
                  else if (q != 1 && q != 2 && q != 3) {
                     q = 1*q-3;
                     c = 3;
                  } 
               } else if (k == 40) { // abaixo
                  if (l != 3) l++;
                  else if (q != 3 && q != 6 && q != 9) {
                     q = 1*q+1;
                     l = 1;
                  } 
               } else if (k == 38) { // acima
                  if (l != 1) l--;
                  else if (q != 1 && q != 4 && q != 7) {
                     q = 1*q-1;
                     l = 3;
                  } 
               }
               seleciona_op(q+''+l+''+c);
            }
         }
//         alert(k);
         // so permite apagar e selecionar valores para as opcoes que estao em aberto
         if (string_na_lista(op_sel,op_abertas)) {
            // apagar o numero na opcao selecionada
            if (k == 46 || k == 8 || k == 96 || k == 48) {
               // seta a opção com o valor do número selecionado
               o = document.getElementById('sel_op_'+op_sel);
               o.innerHTML = '&nbsp;';
               o.style.backgroundColor = '';
               op_erro = retira_erro(op_erro);
            }
            // se for um numero a tecla pressionada
            if ((k > 96 || k <= 105) || (k > 48 || k <= 57)) {
               numero = retorna_numero(k); // retorna o valor
               if (op_sel && numero) {
                  seleciona_num(numero);
                  num_sel = numero;
               }
            }
         }
      }
      // retorna uma lista com as opções em ordem
      function gera_lista_op() {
         var lista = new String;
         var ordem1 = new String;
         var ordem2 = new String;
         var ordem3 = new String;
         var opcao = new String;
         // ordem dos quadrados
         ordem1 = '1,4,7';
         ordem1 = ordem1.split(',');
         ordem2 = '2,5,8';
         ordem2 = ordem2.split(',');
         ordem3 = '3,6,9';
         ordem3 = ordem3.split(',');
         for (var cont=1;cont<=3;cont++) {
            for (var l=1;l<=3;l++) {
               if (cont == 1) {
                  for (q in ordem1) {
                     for (var c=1;c<=3;c++) {
                           opcao = ordem1[q]+''+l+''+c;
                           lista+= (lista.length>0?',':'')+opcao;
                     }
                  }
               } else if (cont == 2) {
                  for (q in ordem2) {
                     for (var c=1;c<=3;c++) {
                           opcao = ordem2[q]+''+l+''+c;
                           lista+= (lista.length>0?',':'')+opcao;
                     }
                  }
               } else if (cont == 3) {
                  for (q in ordem3) {
                     for (var c=1;c<=3;c++) {
                           opcao = ordem3[q]+''+l+''+c;
                           lista+= (lista.length>0?',':'')+opcao;
                     }
                  }
               }
            }
         }
         return(lista);
      }
      // inicializa o jogo com números aleatorios de acordo com o nível escolhido
      function inicializa() {
         // limpa as variaveis
         num_sel; // numero selecionado
         num_sel_ant; // numero selecionado anterior
         op_sel,op_sel_ant; // onclick
         op_sel_ant2; // onmouseover
         op_erro = ''; // lista com opcoes erradas
         op_abertas = ''; // opcoes que o jogador ira preencher
         // controle de pontuacao 
         tentativas = 0;
         erros = 0;
         limpa_numeros(); // limpa numeros
         limpa_cor(); // limpa cor
         var numero;
         var q,l,c;
         /*
         Dados iniciais para gerar o jogo:
         1  2  3 | 4  5  6 | 7  8  9
         4  5  6 | 7  8  9 | 1  2  3
         7  8  9 | 1  2  3 | 4  5  6
         ---------------------------
         2  3  4 | 5  6  7 | 8  9  1
         5  6  7 | 8  9  1 | 2  3  4
         8  9  1 | 2  3  4 | 5  6  7
         ---------------------------
         3  4  5 | 6  7  8 | 9  1  2
         6  7  8 | 9  1  2 | 3  4  5
         9  1  2 | 3  4  5 | 6  7  8
         */
         // lista com os indices em ordem
         var lista = new String(gera_lista_op());
         lista = lista.split(',');
         // lista com os valores iniciais
         var inicial = new String('1,2,3,4,5,6,7,8,9,4,5,6,7,8,9,1,2,3,7,8,9,1,2,3,4,5,6,2,3,4,5,6,7,8,9,1,5,6,7,8,9,1,2,3,4,8,9,1,2,3,4,5,6,7,3,4,5,6,7,8,9,1,2,6,7,8,9,1,2,3,4,5,9,1,2,3,4,5,6,7,8');
         inicial = inicial.split(',');
         var contador = 0; // controla o indice do array inicial
         var posicao = 0;
         for (posicao;posicao<lista.length;posicao++) {
            q = lista[posicao].substr(0,1);
            l = lista[posicao].substr(1,1);
            c = lista[posicao].substr(2,1);
            // array contendo os valores de cada opção
            numeros[q][l][c] = inicial[contador];
            // seta os valores na tabela
            var o = document.getElementById('sel_op_'+q+l+c);
            o.innerHTML = inicial[contador];
            o.style.color = 'blue'; // cor dos valores iniciais
            if (o) o.style.backgroundColor = '';
            contador++;
         }
         // troca os valores entre os quadrados maiores, aleatoriamente, criando um jogo desordenado
         // ex.: 1 por 5 - 2 por 7 - 7 por 3
         var troca = 0;
         for (troca;troca<=20;troca++) {
            var de,por;
            while (true) {
               de = rand(1,9);
               por = rand(1,9);
               if (de != por) break;
               else continue;
            }
            for (var quadrado=1;quadrado<=9;quadrado++) {
               var opcoes_quadrado = new String(retorna_op_quadrado(quadrado));
               opcoes_quadrado = opcoes_quadrado.split(',');
               for (posicao=0;posicao<opcoes_quadrado.length;posicao++) {
                  q = opcoes_quadrado[posicao].substr(0,1);
                  l = opcoes_quadrado[posicao].substr(1,1);
                  c = opcoes_quadrado[posicao].substr(2,1);
                  if (numeros[q][l][c] == de) {
                     numeros[q][l][c] = por;
                     var o = document.getElementById('sel_op_'+q+l+c);
                     o.innerHTML = por;
                  } else if (numeros[q][l][c] == por) {
                     numeros[q][l][c] = de;
                     var o = document.getElementById('sel_op_'+q+l+c);
                     o.innerHTML = de;
                  }
               }
            }
         }
         // define a quantidade de números que serão apagados
         var quantidade = 0;
         var nivel;
         if (!nivel) nivel = 1;
         if (nivel == 1) quantidade = 4;
         else if (nivel == 2) quantidade = 5;
         else if (nivel == 3) quantidade = 6;
         else if (nivel == 4) quantidade = 7;
         else if (nivel == 5) quantidade = 8;
         // apaga alguns números de acordo com o nível escolhido
         for (var quadrado=1;quadrado<=9;quadrado++) {
            var opcoes_quadrado = new String(retorna_op_quadrado(quadrado));
            opcoes_quadrado = opcoes_quadrado.split(',');
            var contador = 1;
            do {
               var posicao = rand(0,8);
               var o = document.getElementById('sel_op_'+opcoes_quadrado[posicao]);
               if (o.innerHTML && o.innerHTML != '&nbsp;') {
                  o.innerHTML = '&nbsp;';
                  contador++;
                  op_abertas+= (op_abertas.length>0?',':'')+opcoes_quadrado[posicao];
                  o.style.color = 'black'; // cor dos valores que serao digitados pelo jogador
               }
            } while(contador<=quantidade);
         }
         // variaveis para controle do tempo de jogo
         tempo_total;
         tempo_seg = -1;
         tempo_min = "0"+0;
         tempo_hor = "0"+0;
         clearTimeout(temporizador); //  limpa o temporizador
         atual(); 
      }
   </SCRIPT>
   <STYLE>
      body {
         font-family: arial, helvetica, verdana;
         font-size: 15px;
      }
      table {
         font-family: arial, helvetica, verdana;
         font-size: 15px;
      }
      .table2 {
         font-family: arial, helvetica, verdana;
         font-size: 10px;
      }
      .td2 {
         background-color: white;
         width: 25px; height: 25px;
         text-align: center;
      }
   </STYLE>
   <HEAD>
      <TITLE>
         SUDOKU
      </TITLE>
   </HEAD>
   <BODY>
      <CENTER>
         <SPAN ID='teste'></SPAN>
         <FORM METHOD="post">
            <BR>
            S U D O K U
            <BR>
            <BR>
            <TABLE BORDER=1 WIDTH='70%' CLASS='table2' CELLSPACING=1>
               <TR>
                  <TD WIDTH='20%'>
                     NIVEL: <SPAN ID='nivel'></SPAN>
                  </TD>
                  <TD WIDTH='25%'>
                     TENTATIVAS: <SPAN ID='tentativas'>0</SPAN>
                  </TD>
                  <TD WIDTH='25%'>
                     ERROS: <SPAN ID='erros'>0</SPAN>
                  </TD>
                  <TD WIDTH='40%'>
                     TEMPO: <SPAN ID='tempo'></SPAN>
                  </TD>
               </TR>
            </TABLE>
            <BR>
            <TABLE CELLSPACING=1>
               <TR>
                  <?
                     $l = 1;
                     $c = 1;
                     $q = 1;
                     for ($i=1;$i<=3;$i++) {
                        echo '<TD>';
                        for ($j=1;$j<=3;$j++) {
                           echo "<TABLE ID='q_$j' BORDER=3>";
                              for ($k=1;$k<=3;$k++) {
                                 echo '<TR>';
                                 for ($m=1;$m<=3;$m++) {
                                    if ($l > 3) $l = 1;
                                    if ($c > 3) $c = 1;
                                    echo "<TD CLASS='td2' ID='sel_op_$q$l$c' ONCLICK='seleciona_op($q$l$c)'; ONMOUSEOVER='muda_cor_op($q$l$c)';>&nbsp</TD>";
                                    $c++; // coluna
                                    echo "\n";
                                 }
                                 echo "</TR>";
                                 $l++; // linha
                              }
                           echo '</TABLE>';
                           $q++; // quadrado
                        }
                        echo '</TD>';
                     }
                  ?>
               </TR>
            </TABLE>
            <BR>
            <BR>
            <TABLE BORDER=1 CELLSPACING=1>
               <TR>
               <TR>
                  <TD CLASS="td2" ID="sel_num_0" ONCLICK="seleciona_num(0);" ONMOUSEOVER='muda_cor_op(0)'>&nbsp</TD>
                  <TD CLASS="td2" ID="sel_num_1" ONCLICK="seleciona_num(1);" ONMOUSEOVER='muda_cor_op(1)'>1</TD>
                  <TD CLASS="td2" ID="sel_num_2" ONCLICK="seleciona_num(2);" ONMOUSEOVER='muda_cor_op(2)'>2</TD>
                  <TD CLASS="td2" ID="sel_num_3" ONCLICK="seleciona_num(3);" ONMOUSEOVER='muda_cor_op(3)'>3</TD>
                  <TD CLASS="td2" ID="sel_num_4" ONCLICK="seleciona_num(4);" ONMOUSEOVER='muda_cor_op(4)'>4</TD>
                  <TD CLASS="td2" ID="sel_num_5" ONCLICK="seleciona_num(5);" ONMOUSEOVER='muda_cor_op(5)'>5</TD>
                  <TD CLASS="td2" ID="sel_num_6" ONCLICK="seleciona_num(6);" ONMOUSEOVER='muda_cor_op(6)'>6</TD>
                  <TD CLASS="td2" ID="sel_num_7" ONCLICK="seleciona_num(7);" ONMOUSEOVER='muda_cor_op(7)'>7</TD>
                  <TD CLASS="td2" ID="sel_num_8" ONCLICK="seleciona_num(8);" ONMOUSEOVER='muda_cor_op(8)'>8</TD>
                  <TD CLASS="td2" ID="sel_num_9" ONCLICK="seleciona_num(9);" ONMOUSEOVER='muda_cor_op(9)'>9</TD>
               </TR>                                                         
               </TR>                                                         
            </TABLE>                                                         
            <INPUT TYPE="button" VALUE="Iniciar" ONCLICK="inicializa()">
         </FORM>
      </CENTER>       
   </BOBY>            
</HTML>               
