import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CopyOptionComponent } from './copy-option.component';

describe('CopyOptionComponent', () => {
  let component: CopyOptionComponent;
  let fixture: ComponentFixture<CopyOptionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CopyOptionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CopyOptionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
