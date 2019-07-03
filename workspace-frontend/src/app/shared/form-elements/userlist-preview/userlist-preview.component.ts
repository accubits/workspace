import { Component, OnInit } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-userlist-preview',
  templateUrl: './userlist-preview.component.html',
  styleUrls: ['./userlist-preview.component.scss']
})
export class UserlistPreviewComponent implements OnInit {
  userlistingdrop:boolean =  false;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

}
