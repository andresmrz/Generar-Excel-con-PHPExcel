
/**
*
 * (c) 2018 Mauricio
 * Author: Andrés M. Rodríguez Z.
 *
 * License: no disponible
 */

/**
*
* 
*
 */

//// funcion auxiliar

function nulo(texto)
{
    if(texto === '')
    {
        return ' ';
    }
    else
    {
        return texto;
    }
}

///

function tablaToExcel(tabla,descripcion,hoja,titulo,nombre,autor)
{
    var datosTituloTabla = document.querySelectorAll('#' + tabla + ' thead tr');
    var datosTabla = document.querySelectorAll('#' + tabla + ' tbody tr');
    var tituloTabla = '';
    var contenido = '';
    var cantidadColumnas = 0;

    if((datosTabla.length + datosTituloTabla.length) > 0)
    {
        var coma = false;

        for(var i = 0;i < datosTituloTabla.length;i++)
        {
            var fila = datosTituloTabla[i];

            cantidadColumnas = (cantidadColumnas < fila.children.length)?fila.children.length:cantidadColumnas;

            for(var j = 0;j < fila.children.length;j++)
            {
                if(tituloTabla === '' || coma)
                {
                    tituloTabla += (fila.children[j].textContent).trim() + '*' + fila.children[j].rowSpan + '-' + fila.children[j].colSpan;
                    coma = false;
                }
                else
                {
                    tituloTabla += '+' + (fila.children[j].textContent).trim() + '*' + fila.children[j].rowSpan + '-' + fila.children[j].colSpan;
                }
            }

            if(i < (datosTituloTabla.length - 1))
            {
                tituloTabla += ';';
                coma = true;
            }
        }

        coma = false;

        for(var i = 0;i < datosTabla.length;i++)
        {
            var fila = datosTabla[i];

            for(var j = 0;j < fila.children.length;j++)
            {
                if(contenido === '' || coma)
                {
                    contenido += nulo((fila.children[j].textContent).trim());
                    coma = false;
                }
                else
                {
                    contenido += '+' + (fila.children[j].textContent).trim();
                }
            }

            if(i < (datosTabla.length - 1))
            {
                contenido += ';';
                coma = true;
            }
        }

        var html = '<form action="" method="post" id="formulario-generar-excel">'
        			+ '<input type="text" hidden name="formulario-generar-excel-descripcion" id="formulario-generar-excel-descripcion">'
        			+ '<input type="text" hidden name="formulario-generar-excel-hoja" id="formulario-generar-excel-hoja">'
        			+ '<input type="text" hidden name="formulario-generar-excel-titulo" id="formulario-generar-excel-titulo">'
        			+ '<input type="text" hidden name="formulario-generar-excel-tituloTabla" id="formulario-generar-excel-tituloTabla">'
        			+ '<input type="text" hidden name="formulario-generar-excel-contenido" id="formulario-generar-excel-contenido">'
        			+ '<input type="text" hidden name="formulario-generar-excel-nombre" id="formulario-generar-excel-nombre">'
        			+ '<input type="text" hidden name="formulario-generar-excel-autor" id="formulario-generar-excel-autor" value="">'
    			+ '</form>';

    	document.body.innerHTML += html;

        document.getElementById('formulario-generar-excel').action = '../php/generarExcel.php';
        document.getElementById('formulario-generar-excel-descripcion').value = descripcion;
        document.getElementById('formulario-generar-excel-hoja').value = hoja;
        document.getElementById('formulario-generar-excel-titulo').value = titulo + '*' + cantidadColumnas;
        document.getElementById('formulario-generar-excel-tituloTabla').value = tituloTabla;
        document.getElementById('formulario-generar-excel-contenido').value = contenido;
        document.getElementById('formulario-generar-excel-nombre').value = nombre;
        document.getElementById('formulario-generar-excel-autor').value = autor;

        document.getElementById('formulario-generar-excel').submit();
        document.getElementById('formulario-generar-excel').action = '';
    }
    else
    {
        alert('No hay datos para exportar');
    }
}

