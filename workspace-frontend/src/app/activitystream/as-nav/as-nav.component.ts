import { Component, OnInit } from '@angular/core';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import { SettingsApiService } from '../../shared/services/settings-api.service';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service'

@Component({
  selector: 'app-as-nav',
  templateUrl: './as-nav.component.html',
  styleUrls: ['./as-nav.component.scss']
})
export class AsNavComponent implements OnInit {
  // as_tab = 'as_recent';
  showSearch:boolean=false;
  constructor(public settingsDataService: SettingsDataService,
    public actStreamDataService: ActStreamDataService,
    public SettingsApiService: SettingsApiService) { }
  
  ngOnInit() {
    this.SettingsApiService.fetchProfileDetailsEdit().subscribe((result: any) => {
     this.actStreamDataService.CommentsAndResponses.imageUrl = result.data.imageUrl;
    });
  }
}
