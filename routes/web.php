<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\SupervisorController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('errorGeneral', 'HomeController@errorGeneral')->name('errorGeneral');
Route::get('error', function(){ 
    abort(500);
});
Route::get('/user', [HomeController::class, 'getUser'])->name('user');
Auth::routes(); Route::get('/', [HomeController::class, 'index'])->name('home');
//Route::resource('/estudiante', EstudianteController::class);


//Route::get('/home', [HomeController::class, 'index'])->name('home');
//Route::resource('/docente', DocenteController::class);
//Route::resource('/docente', [DocenteController::class, 'index'])->name('index');
//Route::resource('/administrador', AdministradorController::class);


////////////////////////////////administrador///////////////////////

///Route::get('administrador','AdministradorController@AdministradorPrincipal')->name('PrincipalAdministrador');

//Route::get('administrador/informacion','AdministradorController@AdministradorInformacion')->name('InformacionAdministrador');
///////////////////////////////////////asistencia administrador////////////////////////
//Route::get('administrador/asistencias/paralelo','AdministradorController@AsistenciasParaleloAdministrador')->name('AsistenciasParaleloAdministrador');

Route::get('/administrador/informacion',[AdministradorController::class, 'InformacionAdministrador'])->name('InformacionAdministrador');
Route::post('/administrador/informacion/{codpersona}',[AdministradorController::class, 'UpdateAdministrador'])->name('UpdateAdministrador');
//Route::post('/administrador/informacion/{codpersona}','AdministradorController@UpdateAdministrador')->name('UpdateAdministrador');
Route::get('administrador/asistencias/paralelo', [AdministradorController::class, 'AsistenciasParaleloAdministrador'])->name('AsistenciasParaleloAdministrador');
Route::get('/periodos/{codperiodo}',[AdministradorController::class, 'getCalificaciones'])->name('getCalificaciones');
Route::get('/secciones/{codperiodo}',[AdministradorController::class, 'getSecciones'])->name('getSecciones');
Route::get('/paralelos/{codperiodo}/{codseccion}',[AdministradorController::class, 'getParalelos'])->name('getParalelos');
Route::get('/materias/{codperiodo}/{codseccion}/{codparalelo}/{fecha}',[AdministradorController::class, 'getMateriasAlaFecha'])->name('getMateriasAlaFecha');
Route::get('/asistenciaDeEstudiante/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}/{fecha}',[AdministradorController::class, 'getAsistenciaEstudianteFecha'])->name('getAsistenciaEstudianteFecha');
Route::get('/administrador/asistencias/asistenciapdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}/{codperiodoseccionparalelo}/{fecha}',[PdfController::class, 'asistenciaspdfadministrador'])->name('asistenciaspdfadministrador');
Route::get('administrador/asistencias/entre_fechas', [AdministradorController::class, 'AsistenciasParaleloRangoAdministrador'])->name('AsistenciasParaleloRangoAdministrador');
Route::get('/materias/{codperiodo}/{codseccion}/{codparalelo}',[AdministradorController::class, 'getMaterias'])->name('getMaterias');
/////////////////////////////////////////////

Route::get('administrador/justificacion/estudiante', [AdministradorController::class, 'RegistroDeJustificacionDeEstudianteAdministrador'])->name('RegistroDeJustificacionDeEstudianteAdministrador');
Route::get('/buscarNumeroSolicitud/{codperiodo}/{cedpersona}/{numeroSolicitud}',[AdministradorController::class, 'getSolicitudJustificacion'])->name('getSolicitudJustificacion');

Route::get('/materiasJustificacion/{codperiodo}/{cedpersona}/{fecha}',[AdministradorController::class, 'getMateriaParaJustificacion'])->name('getMateriaParaJustificacion');
Route::get('/datosDePersona/{ultimoPeriodo}/{cedpersona}',[AdministradorController::class, 'getDatosPersona'])->name('getDatosPersona');
Route::get('/datosDePersona/{ultimoPeriodo}/{codpersona}',[AdministradorController::class, 'getDatosPersona'])->name('getDatosPersona');
Route::get('/estadoDeAsistenciaAMateria/{codpersona}/{codmateria}/{fecha}',[AdministradorController::class, 'getEstadoDeAsistenciaAMateria'])->name('getEstadoDeAsistenciaAMateria');

Route::post('/asistencia/individual/actualizar',[AdministradorController::class,'postAsistenciaIndividualActualizar'])->name('postAsistenciaIndividualActualizar');

Route::get('administrador/justificacion/curso', [AdministradorController::class, 'RegistroDeJustificacionDeCursoAdministrador'])->name('RegistroDeJustificacionDeCursoAdministrador');
Route::post('/asistencia/curso/actualizar',[AdministradorController::class,'postAsistenciaCursoActualizar'])->name('postAsistenciaCursoActualizar');
///////////////////////////////////////////////////Listar justificaciones/////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('administrador/Listar_justificaciones', [AdministradorController::class, 'ListaJustificacionesAdministrador'])->name('ListaJustificacionesAdministrador');
Route::get('/lista_jusatificaciones_administrador/{ultimoPeriodo}/{codseccion}/{codparalelo}',[AdministradorController::class, 'getListaJustificacionesAdministrador'])->name('getListaJustificacionesAdministrador');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/fechas_materia/{codperiodoseccionparalelo}/{codmateria}',[AdministradorController::class, 'getFechas_Materia'])->name('getFechas_Materia');
Route::get('/asistenciaDeEstudianteRango/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}/{fechainicio}/{fechafin}',[AdministradorController::class, 'getAsistenciaEstudianteRangoFecha'])->name('getAsistenciaEstudianteRangoFecha');
Route::get('/administrador/asistencias/asistenciaEntreFechaspdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}/{fechaInicio}/{fechaFina}',[PdfController::class, 'asistenciasEntreFechasPdfAdmininstrador'])->name('asistenciasEntreFechasPdfAdmininstrador');

Route::get('administrador/asistencias/porcentaje', [AdministradorController::class, 'ProcentajesDeAsistenciaEstudiante'])->name('ProcentajesDeAsistenciaEstudiante');
Route::get('/porcentajeDeAsistenciaIndividual/{cedpersona}',[AdministradorController::class, 'getAsistenciaIndividualPorcentaje'])->name('getAsistenciaIndividualPorcentaje');
Route::get('/administrador/asistencias/asistenciaPorcentajeIndividualpdf/{codpersona}',[PdfController::class, 'asistenciasPorcentajeIndividualPdfAdmininstrador'])->name('asistenciasPorcentajeIndividualPdfAdmininstrador');

////reportes/////////////////
Route::get('administrador/reportes', [AdministradorController::class, 'FormatoAsistencia'])->name('FormatoAsistencia');
Route::get('/lista_estudiante/{codperiodo}/{codseccion}/{codparalelo}',[AdministradorController::class, 'getListaEstudiantesActivos'])->name('getListaEstudiantesActivos');
Route::get('/administrador/reportes/formatoDeAsistenciapdf/{codperiodo}/{codseccion}/{codparalelo}',[PdfController::class, 'formatoDeAsistenciaspdfadministrador'])->name('formatoDeAsistenciaspdfadministrador');
////////horario de clases///////
Route::get('administrador/horarioclases',[AdministradorController::class, 'AdministradorHorarioClasesEstudiantes'])->name('AdministradorHorarioClasesEstudiantes');
Route::get('/periodoshorarios/{codperiodo}',[AdministradorController::class, 'getHorarios'])->name('getHorarios');
Route::get('/administrador/horarioclasespdf/{codfase}',[PdfController::class, 'horarioclasesAdministradorpdf'])->name('horarioclasesAdministradorpdf');
/////////reporte asistencia por materia//////////////////////////////
Route::get('administrador/asistencia_reporte',[AdministradorController::class, 'AdministradorReporteDeAsistenciaMateria'])->name('AdministradorReporteDeAsistenciaMateria');
Route::get('/materias_asistencia/{codperiodo}/{codseccion}/{codparalelo}',[AdministradorController::class, 'getMateriasReporteAsistencia'])->name('getMateriasReporteAsistencia');
Route::get('/administrador/asistenciaDeMateriapdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}',[PdfController::class, 'asistenciasMateriaTotalPdfAdmininstrador'])->name('asistenciasMateriaTotalPdfAdmininstrador');
//////////////////////////cuadrogeneral excel/////////////////////////
Route::get('administrador/cuadro_general',[AdministradorController::class, 'AdministradorReporteExcelDeAsistenciaMateria'])->name('AdministradorReporteExcelDeAsistenciaMateria');
Route::get('/paralelo_cuadro_general/{codperiodo}',[AdministradorController::class, 'getParalelosCuadroGeneral'])->name('getParalelosCuadroGeneral');
Route::get('/administrador/cuadro_general_excel/{codperiodo}/{codseccion}/{codparalelo}/{codperiodoseccionparalelo}',[ExcelController::class, 'cuadroGeneralExcel'])->name('cuadroGeneralExcel');

Route::get('/fecha_control/{codperiodo}/{codseccion}',[AdministradorController::class, 'getFechasLimiteInicioActual'])->name('getFechasLimiteInicioActual');
Route::get('/fechas_de_control/{codperiodo}/{codpersona}',[AdministradorController::class, 'getFechasLimiteInicioActualJustificacion'])->name('getFechasLimiteInicioActualJustificacion');

//Route::get('administrador/cuadro_general',[AdministradorController::class, 'AdministradorReporteExcelDeAsistenciaMateria'])->name('AdministradorReporteExcelDeAsistenciaMateria');
///////////////////////////////////////////////////////////////////



//Route::get('/estudiantesPresentes/{codmateria}/{fecha}',[AdministradorController::class, 'getEstudiantesPresentes'])->name('getEstudiantesPresentes');


//Route::get('/estudiantesDeParalelo/{codperiodo}/{codseccion}/{codparalelo}',[AdministradorController::class, 'getEstudiantesDelParalelo'])->name('getEstudiantesDelParalelo');





/////////////////////////////////////////////////////////Docente//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/docente', [DocenteController::class, 'index'])->name('homeDocente');
Route::get('/docente/informacion',[DocenteController::class, 'DocenteInformacion'])->name('DocenteInformacion');
Route::post('/docente/informacion/{codpersona}',[DocenteController::class, 'UpdateDocente'])->name('UpdateDocente');
///////////////////////////////////////////////////////////////tomar lista docente////////////////////////////////////////////////////////////////////
Route::get('docente/tomarLista', [DocenteController::class, 'TomarListaDocente'])->name('TomarListaDocente');
Route::post('/cambiar_asistencia', [DocenteController::class, 'postActualizarAsistenciaTomarLista'])->name('postActualizarAsistenciaTomarLista');
///////////////////////////////////////////////////////////////Asistencia fecha deterninada////////////////////////////////////////////////////////////////////
Route::get('docente/asistencia_fecha', [DocenteController::class, 'AsistenciaFechaDeterminada'])->name('AsistenciaFechaDeterminada');
Route::get('/materias_docente/{ultimoPeriodo}/{codseccion}/{codparalelo}',[DocenteController::class, 'getMateriasDeDocenteEnElPeriodo'])->name('getMateriasDeDocenteEnElPeriodo');
Route::get('/fecha_materia/{ultimoPeriodo}/{codseccion}/{codparalelo}/{codmateria}',[DocenteController::class, 'getFechasDeMateria'])->name('getFechasDeMateria');
Route::get('/lista_asistencia_por_fecha/{ultimoPeriodo}/{codseccion}/{codparalelo}/{codmateria}/{fecha}',[DocenteController::class, 'getAsistenciaPorFechas'])->name('getAsistenciaPorFechas');
Route::get('/docente/asistenciaPorDiapdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}/{fecha}',[PdfController::class, 'asistenciaspdfDocente'])->name('asistenciaspdfDocente');
/////////////////////////////////////////////////////////////asistencias entrefechas////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('docente/asistencias_fechas', [DocenteController::class, 'AsistenciaEntreFechas'])->name('AsistenciaEntreFechas');
Route::get('/lista_asistencia_entre_fechas/{ultimoPeriodo}/{codseccion}/{codparalelo}/{codmateria}/{fecha}',[DocenteController::class, 'getAsistenciaPorFechas'])->name('getAsistenciaPorFechas');
Route::get('/docente/asistenciaEntreFechaspdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}/{fechaInicio}/{fechaFina}',[PdfController::class, 'asistenciasEntreFechasPdfDocente'])->name('asistenciasEntreFechasPdfDocente');

Route::get('docente/Porcentaje', [DocenteController::class, 'PorcentajesDeAsistenciaDocente'])->name('PorcentajesDeAsistenciaDocente');
Route::get('/porcentajesAsistenciaCurso/{ultimoPeriodo}/{codseccion}/{codparalelo}/{codmateria}',[DocenteController::class, 'getAsistenciaPorcentajeCurso'])->name('getAsistenciaPorcentajeCurso');
Route::get('/docente/asistenciaPorcentajeCursopdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}',[PdfController::class, 'porcentajeAsistenciaCursoPdf'])->name('porcentajeAsistenciaCursoPdf');
/////////////////////////////////////////////////////////////listar justificaciones docente////////////////////////////////////////
Route::get('docente/Listar_justificaciones', [DocenteController::class, 'ListaJustificacionesDocente'])->name('ListaJustificacionesDocente');
Route::get('/lista_jusatificaciones/{ultimoPeriodo}/{codseccion}/{codparalelo}/{codmateria}',[DocenteController::class, 'getListaJustificacionesDocente'])->name('getListaJustificacionesDocente');
/////////////////////////////////////////////////////////////listar de estudiantes////////////////////////////////////////
Route::get('docente/lista_alumnos', [DocenteController::class, 'ListaDeAlumnosDocente'])->name('ListaDeAlumnosDocente');
Route::get('/lista_alumnos_paralelo/{ultimoPeriodo}/{codseccion}/{codparalelo}',[DocenteController::class, 'getListaParalelo'])->name('getListaParalelo');
Route::get('/docente/listaParaleloDocentepdf/{codperiodo}/{codseccion}/{codparalelo}',[PdfController::class, 'listaParaleloDocentePDF'])->name('listaParaleloDocentePDF');
/////////////////////////////////////////////////horarios de clase/////////////////////////

Route::get('docente/horarios_clases',[DocenteController::class, 'HorarioClasesEstudiantesDocente'])->name('HorarioClasesEstudiantesDocente');
//Route::get('/periodoshorarios/{codperiodo}',[AdministradorController::class, 'getHorarios'])->name('getHorarios');
Route::get('/docente/reporte_horario_clases/{codfase}',[PdfController::class, 'horarioclasesDocentepdf'])->name('horarioclasesDocentepdf');
/////////reporte asistencia por materia//////////////////////////////
Route::get('docente/asistencia_reporte_docente',[DocenteController::class, 'DocenteReporteDeAsistenciaMateria'])->name('DocenteReporteDeAsistenciaMateria');
Route::get('/docente/asistenciaDeMateriaDocentepdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}',[PdfController::class, 'asistenciasMateriaTotalPdfDocente'])->name('asistenciasMateriaTotalPdfDocente');

////////////////////////////////////////////Alumno////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/estudiante', [EstudianteController::class, 'index'])->name('homeEstudiante');
Route::get('/estudiante/informacion',[EstudianteController::class, 'EstudianteInformacion'])->name('EstudianteInformacion');
Route::post('/estudiante/informacion/{codpersona}',[EstudianteController::class, 'UpdateEstudiante'])->name('UpdateEstudiante');
///////////////////////////////////////////////////////////////Asistencia fecha deterninada////////////////////////////////////////////////////////////////////
Route::get('estudiante/asistencia_fecha', [EstudianteController::class, 'AsistenciaFechaDeterminadaEstudiante'])->name('AsistenciaFechaDeterminadaEstudiante');
Route::get('/lista_asistencia_por_fecha_estudiante/{ultimoPeriodo}/{codseccion}/{fecha}',[EstudianteController::class, 'getAsistenciaPorFechas'])->name('getAsistenciaPorFechas');

/////////////////////////////////////////////////////////////asistencias entrefechas////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('estudiante/asistencias_fechas', [EstudianteController::class, 'AsistenciaEntreFechasEstudiante'])->name('AsistenciaEntreFechasEstudiante');

Route::get('/fechas_fase/{codperiodoseccionparalelo}/{codfase}',[EstudianteController::class, 'getFechasFase'])->name('getFechasFase');
Route::get('/asistencia_estudiante_entre_fechas/{codfase}/{fechainicio}/{fechafin}',[EstudianteController::class, 'getAsistenciaEstudianteEntreFecha'])->name('getAsistenciaEstudianteEntreFecha');

/////////////////////////////////////////////////////////////porcentajes de asistencia////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////asistencias por modulo////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('estudiante/asistencias_porcentaje', [EstudianteController::class, 'MostrarPorcentajeAsistenciaEstudiante'])->name('MostrarPorcentajeAsistenciaEstudiante');

/////////////////////////////////////////////////////////////listar horario////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('estudiante/listar_horario', [EstudianteController::class, 'ListaHorarioEstudiante'])->name('ListaHorarioEstudiante');
Route::get('/horarioclases2/{codfase}', [EstudianteController::class, 'horarioclases'])->name('horarioclases');
Route::get('/estudiante/horarioClasesEstudiantepdf/{codfase}',[PdfController::class, 'horarioclasesEstudiantepdf'])->name('horarioclasesEstudiantepdf');

/////////////////////////////////////////////////////////////listar horario////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('estudiante/acta_asistencia', [PdfController::class, 'ActaAsistenciaEstudiante'])->name('ActaAsistenciaEstudiante');
////////////////////////////////////////////Supervisor////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/supervisor', [SupervisorController::class, 'index'])->name('homeSupervisor');
Route::get('/supervisor/informacion',[SupervisorController::class, 'SupervisorInformacion'])->name('SupervisorInformacion');
///////////////////////////////////////////////////////////////Asistencia fecha deterninada////////////////////////////////////////////////////////////////////
Route::get('supervisor/asistencia_por_dia', [SupervisorController::class, 'AsistenciaFechaDeterminadaSupervisor'])->name('AsistenciaFechaDeterminadaSupervisor');
Route::get('/materias_supervisor/{codperiodo}/{codseccion}/{codparalelo}',[SupervisorController::class, 'getMateriasSupervisor'])->name('getMateriasSupervisor');
Route::get('/supervisor/asistencia_por_dia_Supervisorpdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}/{fecha}',[PdfController::class, 'asistenciaPorDiaSupervisorpdf'])->name('asistenciaPorDiaSupervisorpdf');
///////////////////////////////////////////////////////////////Asistencia entre fechas////////////////////////////////////////////////////////////////////
Route::get('supervisor/asistencia_entre_fechas_supervisor', [SupervisorController::class, 'AsistenciaEntreFechasSupervisor'])->name('AsistenciaEntreFechasSupervisor');
Route::get('/supervisor/asistencia_entre_fechas_supervisor_pdf/{codperiodo}/{codseccion}/{codparalelo}/{codmateria}/{fechaInicio}/{fechaFina}',[PdfController::class, 'asistenciasEntreFechasPdfSupervisor'])->name('asistenciasEntreFechasPdfSupervisor');
