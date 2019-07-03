import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SecPollComponent } from './sec-poll.component';

describe('SecPollComponent', () => {
  let component: SecPollComponent;
  let fixture: ComponentFixture<SecPollComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecPollComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SecPollComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
