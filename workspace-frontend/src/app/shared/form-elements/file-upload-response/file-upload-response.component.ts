import { Component, OnInit } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-file-upload-response',
  templateUrl: './file-upload-response.component.html',
  styleUrls: ['./file-upload-response.component.scss']
})
export class FileUploadResponseComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

}
