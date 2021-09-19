<!-- Inicio :: Modal Relatorio Instrucoes -->
<div class="modal fade" id="modalRelatorioInstrucoes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-row-edit="0">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="label">Instruções do Relatório</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<?= !empty($instrucoes) ? $instrucoes : "Sem instruções"; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary danger" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>
<!-- Fim :: Modal Relatorio Instrucoes -->