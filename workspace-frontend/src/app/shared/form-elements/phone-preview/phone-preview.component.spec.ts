import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PhonePreviewComponent } from './phone-preview.component';

describe('PhonePreviewComponent', () => {
  let component: PhonePreviewComponent;
  let fixture: ComponentFixture<PhonePreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PhonePreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PhonePreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
