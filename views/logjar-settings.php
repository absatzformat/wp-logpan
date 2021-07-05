<?php
	/**
	 * $options
	 */
?>
<div class="wrap">
	<h1><?= __('Logjar Logger', 'logjar') ?></h1>

	<form method="post" action="options.php">

		<table class="form-table">
			<tr>
				<th><label for="logjar_server_address">Server</label></th>
				<td>
					<input id="logjar_server_address" class="regular-text" name="logjar_options[server_address]" type="text" value="<?= esc_attr($options['server_address'] ?? '') ?>">
					Port <input class="small-text" name="logjar_options[server_port]" type="text" value="<?= esc_attr($options['server_port'] ?? '') ?>">
					<p class="description">
						Die Serveradresse und Port des Log Servers.<br>
						Mit <code>http(s)://</code> können Sie festlegen, ob eine (un)verschlüsselte Verbindung hergestellt werden soll.
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="logjar_server_token">Token</label></th>
				<td>
					<input id="logjar_server_token" class="regular-text" name="logjar_options[server_token]" type="text" value="<?= esc_attr($options['server_token'] ?? '') ?>">
					<p class="description">
						Bearer Token für die Authentifizierung am Log Server.
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="logjar_log_path">Pfad</label></th>
				<td>
					<input id="logjar_log_path" name="logjar_options[log_path]" type="text" value="<?= esc_attr($options['log_path'] ?? '') ?>">
					<p class="description">
						Der Serverpfad für Log Channels. Standard ist <code>/channel</code>.
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="logjar_log_channel">Channel</label></th>
				<td>
					<input id="logjar_log_channel" class="small-text" name="logjar_options[log_channel]" type="number" step="1" min="1" value="<?= esc_attr($options['log_channel'] ?? 1) ?>">
					<p class="description">
						Nummer des Log Channels.
					</p>
				</td>
			</tr>
		</table>

		<h2 class="title">Logging</h2>
		<p>
			Hier können Sie das <code>Loglevel</code> festlegen, unter welchem PHP Fehlermeldungen geloggt werden sollen.
		</p>

		<table class="form-table">
			<tr>
				<th><label for="logjar_log_level">Loglevel</label></th>
				<td>
					<select name="logjar_options[log_level]" id="logjar_log_level">
					<?php $level = $options['log_level'] ?? 0; ?>
						<option value="0"<?= $level == 0 ? 'selected' : '' ?>>Keine</option>
						<option value="<?= E_ALL ?>"<?= $level == E_ALL ? 'selected' : '' ?>>Alle</option>
					</select>
				</td>
			</tr>
		</table>

		<?php settings_fields('logjar_options'); ?>
		<?php submit_button(); ?>
	</form>
</div>