import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ApplyConfirmComponent } from './apply-confirm.component';

describe('ApplyConfirmComponent', () => {
  let component: ApplyConfirmComponent;
  let fixture: ComponentFixture<ApplyConfirmComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ApplyConfirmComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ApplyConfirmComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
