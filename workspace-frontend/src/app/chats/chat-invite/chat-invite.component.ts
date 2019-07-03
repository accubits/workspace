import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ChatDataService } from '../../shared/services/chat-data.service';

@Component({
  selector: 'app-chat-invite',
  templateUrl: './chat-invite.component.html',
  styleUrls: ['./chat-invite.component.scss']
})
export class ChatInviteComponent implements OnInit {
  addpple = 'search';
  public assetUrl = Configs.assetBaseUrl;
  constructor(public chatDataService: ChatDataService) { }

  ngOnInit() {
  }

}
