<template>
<div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-10">
				<div class="card">
					<div class="card-header">
						Certificados de Estudios
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-2">
								Archivo PDF:
							</div>
							<div class="col-md-8">
								<input type="file" id="upload_file" ref="upload_pdf" @change="change_pdf" accept="application/pdf" name="pdf">
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-2">
								Archivo Foto:
							</div>
							<div class="col-md-8">
								<input type="file" id="upload_photo" ref="upload_photo" @change="change_photo" accept=".jpg" name="upload_photo">
							</div>
						</div>
					</div>
					<div v-if="loaded" class="card-body">
						<div class="row">
							<div class="col-md-4">
								<button v-on:click="getPreview" class="btn btn-primary">
									Vista Preliminar
								</button>
							</div>
							<div v-if="stat_preview" class="col-md-4">
								<button v-on:click="download" class="btn btn-primary">
									Descargar archivo
								</button>
							</div>
						</div>
					</div>
					<div v-if="waiting" class="card-body">
						<img src="images/wait.gif"/>
					</div>
					<div v-if="stat_preview" class="card-body">
						<div class="row">
							<div class="preview col-md-12">
								<iframe :src="filepreview" style="width: 100%; height:50vw; position: relative; allowfullscreen;"></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
	export default {
		props: ['user_id'],
		data() {
			return {
				'waiting' : false,
				'loaded' : false,
				'files_loaded': {
					'photo' : false,
					'PDF' : false,
				},
				'stat_preview' : false,
				'preview' : false,
				'filepreview' : false,
				'namedownload': "",
				'filename' : "",
				'filebase' : "",
				'filephoto' : "",
				'filePDF' : '',
				'originalName': ''
			}
		},

		mounted() {
			console.log('CEComponent.vue mounted.');
		},


		watch: {
			files_loaded: {
				handler: function () {
						if ( this.files_loaded.photo && this.files_loaded.PDF ){
							this.loaded = true;
						} else {
							this.loaded = false;
						}
					},
					deep: true,
			},

		},

		methods: {
			download(){
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
		    var url = protocol+'//'+URLdomain+this.filepreview;
				axios({
						url: url,
            // url: 'http://localhost:8000/my.pdf',
            method: 'GET',
            responseType: 'blob',
        }).then((response) => {
             var fileURL = window.URL.createObjectURL(new Blob([response.data]));
             var fileLink = document.createElement('a');

             fileLink.href = fileURL;
             fileLink.setAttribute('download', this.namedownload);
             document.body.appendChild(fileLink);

             fileLink.click();
        });
			},
			getPreview()
			{
				this.stat_preview = false;
				this.waiting = true;
				var request = {
					user_id: this.user_id,
					filephoto : {
						path: 'images/jpg/',
						filepath: this.filefirma,
					},
					pages : this.filePDF['pages'],
					file_out : {
						filename: this.originalName
					},
				};
// console.log('getPreview request', request);
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
        var url = protocol+'//'+URLdomain+'/api/ce/preview';
        axios.post(url, request).then(response => {
console.log('getPreview response.data: ',response.data);
					this.preview = response.data.success;
					this.filepreview = response.data.filepath+"?"+Date.now();
					this.namedownload = response.data.filename;
					if(!this.preview)
					{
						console.log('error preview, no se grabo');
					}else{
						this.stat_preview = true;
						this.waiting = false;
					}

				}).catch(function (error) {
					console.log('error preview', error);
				});

			},

			change_pdf()
			{
				this.files_loaded.PDF = false;
				this.stat_preview = false;
				this.waiting = true;
				this.originalName = event.target.files[0]['name'];
				var request = new FormData();
				request.append('file_PDF', event.target.files[0]);
				request.append('user_id', this.user_id);
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
        var url = protocol+'//'+URLdomain+'/api/ce/uploadPDF';
        axios.post(url, request).then(response => {
					console.log('change_pdf response', response.data);

        	this.filePDF = response.data;
        	this.files_loaded.PDF = true;
					this.waiting = false;

				}).catch(function (error) {
					console.log('error change_pdf', error);
				});
			},

			change_photo(event)
			{
				this.files_loaded.photo = false;
				this.stat_preview = false;
				var request = new FormData();
				request.append('file_photo', event.target.files[0]);
				request.append('user_id', this.user_id);
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
        var url = protocol+'//'+URLdomain+'/api/ce/uploadPhoto';
        axios.post(url, request).then(response => {
					console.log('change_photo response.data', response.data);

        	this.filefirma = response.data.path + response.data.filename;
        	this.files_loaded.photo = true;

				}).catch(function (error) {
					console.log('error change_photo', error);
				});
			},

		}
	};
</script>

<style type="text/css">
    html, body, div#content { margin:0; padding:0; height:100%; }
    iframe { display:block; width:100%; border:none; }
</style>