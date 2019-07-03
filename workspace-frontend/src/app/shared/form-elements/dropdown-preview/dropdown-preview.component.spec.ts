import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DropdownPreviewComponent } from './dropdown-preview.component';

describe('DropdownPreviewComponent', () => {
  let component: DropdownPreviewComponent;
  let fixture: ComponentFixture<DropdownPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DropdownPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DropdownPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
