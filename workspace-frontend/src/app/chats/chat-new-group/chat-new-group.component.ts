import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ChatDataService } from '../../shared/services/chat-data.service';

@Component({
  selector: 'app-chat-new-group',
  templateUrl: './chat-new-group.component.html',
  styleUrls: ['./chat-new-group.component.scss']
})
export class ChatNewGroupComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
