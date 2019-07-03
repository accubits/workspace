import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormShareComponent } from './form-share.component';

describe('FormShareComponent', () => {
  let component: FormShareComponent;
  let fixture: ComponentFixture<FormShareComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormShareComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormShareComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
