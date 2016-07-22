<?php
	App::uses('CakeEmail', 'Network/Email');

	class FawnoEmail extends CakeEmail {
		protected function _renderTemplates ($content) {
			if (!empty($this->_subject) && empty($this->_viewVars['subject'])) {
				$this->_viewVars['subject'] = $this->_subject;
			}

			$rendered = parent::_renderTemplates($content);

			if (!empty($rendered['html'])) {
				$rendered['html'] = preg_replace('~(img\s.*src\s*=\s*["\'])(file://|file:|cid://|cid:)~iU', '$1cid:', $rendered['html']);
				if (preg_match_all('~img\s[^>]*src\s*=\s*(["\'])cid:([^\1]+)\1~iU', $rendered['html'], $img)) {
					$img = array_unique($img[2]);
					foreach ($img as $file) {
						$fileName = $file;
						$file = realpath($file);
						if (!is_file($file)) throw new SocketException(__d('cake_dev', 'File not found: "%s"', $fileName));
						$cid = sha1($file);
						$this->_attachments['cid:' . $cid] = array('file' => $file, 'contentId' => $cid, 'mimetype' => mime_content_type($file));
						$files['cid:' . $cid] = $fileName;
						$cids['cid:' . $cid] = $cid;
					}
					$rendered['html'] = str_replace($files, $cids, $rendered['html']);
				}
			}

			return $rendered;
		}

		public function attachments ($attachments = null) {
			if ($attachments === null) {
				return $this->_attachments;
			}
			$attach = array();
			foreach ((array)$attachments as $name => $fileInfo) {
				if (!is_array($fileInfo)) {
					$fileInfo = array('file' => $fileInfo);
				}
				if (!isset($fileInfo['file'])) {
					if (!isset($fileInfo['data'])) {
						throw new SocketException(__d('cake_dev', 'No file or data specified.'));
					}
					if (is_int($name)) {
						throw new SocketException(__d('cake_dev', 'No filename specified.'));
					}
					$fileInfo['data'] = chunk_split(base64_encode($fileInfo['data']), 76, "\r\n");
				} else {
					$fileName = $fileInfo['file'];
					$fileInfo['file'] = realpath($fileInfo['file']);
					if (!is_file($fileInfo['file'])) {
						throw new SocketException(__d('cake_dev', 'File not found: "%s"', $fileName));
					}
					if (is_int($name)) {
						$name = basename($fileInfo['file']);
					}
				}
				if (!isset($fileInfo['mimetype'])) {
					$fileInfo['mimetype'] = mime_content_type($fileInfo['file']);
				}
				$attach[$name] = $fileInfo;
			}
			$this->_attachments = $attach;
			return $this;
		}
	}
?>
