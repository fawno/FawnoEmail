<?php
	App::uses('CakeEmail', 'Network/Email');

	class FawnoEmail extends CakeEmail {
		protected function _renderTemplates ($content) {
			if (!empty($this->_subject) && empty($this->_viewVars['subject'])) {
				$this->_viewVars['subject'] = $this->_subject;
			}

			$render = parent::_renderTemplates($content);

			if (!empty($render['html'])) {
				if (preg_match_all('~(["\'])cid:([^\1]+)\1~iU', $render['html'], $img)) {
					$img = array_unique($img[2]);
					foreach ($img as $file) if (is_file($file)) {
						$cid = sha1($file);
						$this->_attachments['cid:' . $cid]['data'] = base64_encode(file_get_contents($file));
						$this->_attachments['cid:' . $cid]['contentId'] = $cid;
						$this->_attachments['cid:' . $cid]['mimetype'] = mime_content_type($file);
						$render['html'] = str_replace($file, $cid, $render['html']);
					}
				}
			}

			return $render;
		}
	}
?>
