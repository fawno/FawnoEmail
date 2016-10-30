<?php
	namespace Fawno\Mailer;

	use Cake\Mailer\Email;

	class FawnoEmail extends Email {
		protected function _renderTemplates ($content) {
			if (!empty($this->_subject) && empty($this->_viewVars['subject'])) {
				$this->_viewVars['subject'] = $this->_subject;
			}

			$rendered = parent::_renderTemplates($content);

			if (!empty($rendered['html'])) {
				preg_match_all('~<img[^>]*src\s*=\s*(["\'])(cid://|file://|cid:|file:)([^\1]+)\1~iU', serialize($this->viewVars), $userFiles);
				$userFiles = array_unique($userFiles[3]);
				preg_match_all('~<img[^>]*src\s*=\s*(["\'])(cid://|file://|cid:|file:)([^\1]+)\1~iU', $rendered['html'], $embebFiles);
				$embebFiles = array_unique($embebFiles[3]);
				$embebFiles = array_diff($embebFiles, $userFiles);
				foreach ($embebFiles as $file) {
					if (is_file($file)) {
						$cid = sha1($file);
						$images['cid:' . $cid] = ['file' => $file, 'contentId' => $cid];
						$files['cid:' . $cid] = '~(<img[^>]*src\s*=\s*)(["\'])(cid://|file://|cid:|file:)' . preg_quote($file) . '\2~iU';
						$cids['cid:' . $cid] = '\1\2cid:' . $cid . '\2';
					}
				}
				if (!empty($images)) {
					$this->addAttachments($images);
					$rendered['html'] = preg_replace($files, $cids, $rendered['html']);
				}
			}

			return $rendered;
		}

		public function attachments ($attachments = null) {
			if ($attachments === null) {
				return $this->_attachments;
			}
			$attach = [];
			foreach ((array) $attachments as $name => $fileInfo) {
				if (!is_array($fileInfo)) {
					$fileInfo = ['file' => $fileInfo];
				}
				if (!isset($fileInfo['file'])) {
					if (!isset($fileInfo['data'])) {
						throw new InvalidArgumentException('No file or data specified.');
					}
					if (is_int($name)) {
						throw new InvalidArgumentException('No filename specified.');
					}
					$fileInfo['data'] = chunk_split(base64_encode($fileInfo['data']), 76, "\r\n");
				} else {
					$fileName = $fileInfo['file'];
					$fileInfo['file'] = realpath($fileInfo['file']);
					if ($fileInfo['file'] === false || !file_exists($fileInfo['file'])) {
						throw new InvalidArgumentException(sprintf('File not found: "%s"', $fileName));
					}
					if (is_int($name)) {
						$name = basename($fileInfo['file']);
					}
				}
				if (!isset($fileInfo['mimetype'])) {
					if (function_exists('mime_content_type')) {
						$fileInfo['mimetype'] = mime_content_type($fileInfo['file']);
					} else {
						$fileInfo['mimetype'] = 'application/octet-stream';
					}
				}
				$attach[$name] = $fileInfo;
			}
			$this->_attachments = $attach;
			return $this;
		}
	}
?>
