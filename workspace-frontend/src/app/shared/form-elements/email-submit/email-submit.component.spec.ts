import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EmailSubmitComponent } from './email-submit.component';

describe('EmailSubmitComponent', () => {
  let component: EmailSubmitComponent;
  let fixture: ComponentFixture<EmailSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EmailSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EmailSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
