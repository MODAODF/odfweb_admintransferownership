<template>
	<div id="transfer-settings" class="section">
		<h2>{{ t('admintransferownership', 'AdminTransferownership') }}</h2>
		<TransferOwnershipAdminDialogue @transfer-submit="transferSubmit" />
		<hr>
		<h2>移交紀錄</h2>
		<TransferOwnershipLog />
	</div>
</template>

<script>
import TransferOwnershipAdminDialogue from './TransferOwnershipAdminDialogue'
import TransferOwnershipLog from './TransferOwnershipLog'

export default {
	name: 'AdminSettings',
	components: {
		TransferOwnershipAdminDialogue,
		TransferOwnershipLog,
	},
	methods: {
		transferSubmit() {
			this.$emit('update-objs')
		}
	}
}
</script>
