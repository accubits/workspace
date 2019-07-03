import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatMembersComponent } from './chat-members.component';

describe('ChatMembersComponent', () => {
  let component: ChatMembersComponent;
  let fixture: ComponentFixture<ChatMembersComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatMembersComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatMembersComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
