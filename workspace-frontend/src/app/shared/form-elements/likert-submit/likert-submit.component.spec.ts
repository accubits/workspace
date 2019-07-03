import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LikertSubmitComponent } from './likert-submit.component';

describe('LikertSubmitComponent', () => {
  let component: LikertSubmitComponent;
  let fixture: ComponentFixture<LikertSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LikertSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LikertSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
